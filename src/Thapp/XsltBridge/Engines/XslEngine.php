<?php

/**
 * This File is part of the vendor\thapp\src\Thapp\XsltBridge\Engines package
 *
 * (c) Thomas Appel <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Thapp\XsltBridge\Engines;

use \XSLTProcessor;
use Thapp\XmlBuilder\XmlBuilder;
use Thapp\XsltBridge\XsltBridge;
use Illuminate\View\Engines\EngineInterface;
use Illuminate\View\Environment;
use Illuminate\Events\Dispatcher;

/**
 * Class: XslEngine
 *
 * @implements EngineInterface
 *
 * @package
 * @version
 * @author Thomas Appel <mail@thomas-appel.com>
 * @license MIT
 */
class XslEngine implements EngineInterface
{
    /**
     * builder
     *
     * @var mixed
     */
    protected $builder;

    /**
     * paramsSet
     *
     * @var mixed
     */
    protected $paramsSet = false;

    /**
     * globalData
     *
     * @var mixed
     */
    protected $globalData;

    /**
     * data
     *
     * @var mixed
     */
    protected $data = array();

    /**
     * eventDispatcher
     *
     * @var mixed
     */
    protected $eventDispatcher;

    /**
     * __construct
     *
     * @param XMLBuilder $builder
     * @param array $globalData
     * @access public
     * @return void
     */
    public function __construct(XmlBuilder $builder, XsltBridge $processor, array $globals = array())
    {
        $this->builder     = $builder;
        $this->processor   = $processor;
        $this->globalData  = $globals;
    }

    /**
     * getData
     *
     * @param array $data
     * @access public
     * @return array
     */
    public function getData(array $data = array())
    {
        return array_merge(array('params' => $this->globalData), $data);
    }

    /**
     * setGlobalData
     *
     * @param array $data
     * @access public
     * @return mixed
     */
    public function addGlobalData(array $data = array())
    {
        $this->globalData = array_merge($this->globalData, $data);
    }

    /**
     * getGlobalData
     *
     * @param array $data
     * @access public
     * @return array
     */
    public function getGlobalData()
    {
        return $this->globalData;
    }

    /**
     * setEventDispatcher
     *
     * @param mixed $dispatcher
     * @access public
     * @return mixed
     */
    public function setEventDispatcher(Dispatcher $dispatcher)
    {
        return $this->eventDispatcher = $dispatcher;
    }

    /**
     * get
     *
     * @param mixed $path
     * @param array $data
     * @access public
     * @return string
     */
    public function get($path, array $data = array())
    {
        // File we want to load
        $file = realpath($path);

        $this->processor->loadXSL($file);

        if (!$this->paramsSet) {
            $this->processor->setParameters($this->getGlobalData());
            $this->builder->load($this->getData($data));
            $this->paramsSet = true;
        } else {
            $this->builder->load($data);
        }

        // Render template
        return $this->processor->render($xml = $this->builder->createXML());
    }
}
