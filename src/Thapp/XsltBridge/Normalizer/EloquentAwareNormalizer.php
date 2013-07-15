<?php

/**
 * This File is part of the Thapp\XsltBridge package
 *
 * (c) Thomas Appel <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Thapp\XsltBridge\Normalizer;

use Thapp\XmlBuilder\Normalizer;
use Illuminate\Support\Contracts\ArrayableInterface;

/**
 * Class: EloquentAwareNormalizer
 *
 * @uses Normalizer
 *
 * @package
 * @version
 * @author Thomas Appel <mail@thomas-appel.com>
 * @license MIT
 */
class EloquentAwareNormalizer extends Normalizer
{
    /**
     * replaceable
     *
     * @var array
     */
    protected static $replaceable = array(
        '_', ':', '#', '+', '.'
    );

    /**
     * ctypeupper
     *
     * @var array
     */
    protected static $ctypeupper = array();

    /**
     * check for arrayable interface
     *
     * {@inheritdoc}
     */
    public function ensureArray($data)
    {
        if ($this->isArrayableInterface($data)) {
            return $data->toArray();
        }

        return parent::ensureArray($data);
    }

    /**
     * normalize
     *
     * @param mixed $value
     * @access public
     * @return mixed
     */
//    public function normalize($value)
//    {
//        //$value = $this->isAllUpperCase($value) ?
//            //strtolower(trim($value, '_-#$%')) :
//            //snake_case(trim($value, '_-#$%'));
//
//        return strtolower(str_replace(array('_', ':', '#', '+', '.'), '-', trim($value, '_-#$%')));
//    }

    private function isAllUpperCase($str)
    {
        $str = preg_replace('/[^a-zA-Z0-9]/', null, $str);
        return ctype_upper($str);
    }

    /**
     * convertObject
     *
     * @param mixed $data
     * @access protected
     * @return mixed
     */
    protected function convertObject($data)
    {
        if ($this->isArrayableInterface($data)) {
            return $data->toArray();
        }
        return parent::convertObject($data);
    }

    /**
     * isArrayableInterface
     *
     * @param mixed $data
     * @access private
     * @return boolean
     */
    private function isArrayableInterface($data)
    {
        return $data instanceof ArrayableInterface;
    }
}
