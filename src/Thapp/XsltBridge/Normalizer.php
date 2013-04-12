<?php

/**
 * This File is part of the Thapp\XsltBridge package
 *
 * (c) Thomas Appel <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Thapp\XsltBridge;

use \ReflectionObject;
use \ReflectionMethod;
use \ReflectionProperty;

/**
 * Class: Normalizer
 *
 *
 * @package
 * @version
 * @author Thomas Appel <mail@thomas-appel.com>
 * @license MIT
 */
class Normalizer
{
    /**
     * objcache
     *
     * @var mixed
     */
    protected $objectcache = array();


    /**
     * ignoredAttributes
     *
     * @var array
     */
    protected $ignoredAttributes = array();

    /**
     * ensureArray
     *
     * @param mixed $data
     * @param mixed $ignoreobjects
     * @access public
     * @return mixed
     */
    public function ensureArray($data, $ignoreobjects = false)
    {
        $out = null;

        switch (true) {
            case is_array($data):
                $out = $this->recursiveConvertArray($data, $ignoreobjects);
                break;
            case $ignoreobjects:
                $out = false;
                break;
            case is_object($data):
                $out = $this->convertObject($data);
                break;
            default:
                break;
        }
        return $out;
    }

    /**
     * recursiveConvertArray
     *
     * @param array $data
     * @param mixed $ignoreobjects
     * @access protected
     * @return array
     */
    protected function recursiveConvertArray(array $data, $ignoreobjects)
    {
        $out = array();

        foreach ($data as $key => $value) {
            $key = $this->normalize($key);

            if (in_array($key, $this->ignoredAttributes)) {
                continue;
            }

            if (is_scalar($value)) {
                $attrValue = $value;
            } else {
                $attrValue = $this->ensureArray($value, $ignoreobjects);
            }

            $out[$this->normalize($key)] = $attrValue;
        }
        return $out;
    }
    /**
     * ensureTraversable
     *
     * @param mixed $data
     * @access public
     * @return array
     */
    public function ensureTraversable($data, $ignoreobjects = false)
    {
        if (!$this->isTraversable($data)) {
            if (is_object($data)) {
                $data = $this->ensureArray($data, $ignoreobjects);
            }
        }

        return $data;
    }

    /**
     * isArrayAble
     *
     * @param ReflectionClass $reflection
     * @access protected
     * @return boolean
     */
    protected function isArrayAble($reflection)
    {
        return $reflection->implementsInterface('Illuminate\Support\Contracts\ArrayableInterface');
    }


    /**
     * convertObject
     *
     * @param mixed $data
     * @access protected
     * @return array
     */
    protected function convertObject($data, $ignoreobjects = false)
    {
        $reflection  = new ReflectionObject($data);

        if ($this->isArrayAble($reflection)) {
            return $data->toArray();
        }

        $methods       = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        $properties    = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $out = array();

        foreach ($methods as $method) {

            if ($this->isGetMethod($method)) {

                $attributeName  = $this->normalize(substr($method->name, 3));
                $attributeValue = $method->invoke($data);

                if (is_callable($attributeValue) || in_array($attributeName, $this->ignoredAttributes)) {
                    continue;
                }

                if (null !== $attributeValue && !is_scalar($attributeValue)) {

                    if (!is_array($attributeValue)) {
                        $hash = spl_object_hash($attributeValue);
                        if (in_array($hash, $this->objectcache)) {
                            continue;
                        }
                        $this->objectcache[] = $hash;
                    }
                    $attributeValue = $this->ensureArray($attributeValue, $ignoreobjects = false);
                }

                $out[$attributeName] = $attributeValue;
            }
        }

        foreach ($properties as $property) {
            $prop =  $property->getName();
            $name = $this->normalize($prop);

            if (in_array($name, $this->ignoredAttributes)) {
                continue;
            }

            try { $out[$name] = $data->{$prop};
            } catch (\Exception $e) {}
        }

        return $out;
    }

    /**
     * normalize
     *
     * @param mixed $value
     * @access public
     * @return string
     */
    public function normalize($value)
    {
        return strtolower(str_replace(array('_', ':', '#', '+'), '-', snake_case(trim($value, '_-#$%'))));
    }

    /**
     * isTraversable
     *
     * @param mixed $data
     * @access protected
     * @return boolean
     */
    protected function isTraversable($data)
    {
        return is_array($data) || $data instanceof \Traversable;
    }

    /**
     * isGetMethod
     *
     * @param mixed $method
     * @access public
     * @return boolean
     */
    public function isGetMethod(\ReflectionMethod $method)
    {
        return 'get' === substr($method->name, 0, 3) && strlen($method->name) > 3 && 0 === $method->getNumberOfRequiredParameters();
    }

    /**
     * setIgnoredAttributes
     *
     * @access public
     * @return mixed
     */
    public function setIgnoredAttributes($attributes)
    {
        if (is_array($attributes)) {
            $this->ignoredAttributes = array_merge($this->ignoredAttributes, $attributes);
            return;
        }
        $this->ignoredAttributes[] = $attributes;
    }
}
