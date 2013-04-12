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
use Thapp\XsltBridge\XMLBuilder;
use Thapp\XsltBridge\XSLTBridge;
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
     * globalData
     *
     * @var mixed
     */
    protected $globalData;

    /**
     * env
     *
     * @var mixed
     */
    protected $events;

    /**
     * __construct
     *
     * @param XMLBuilder $builder
     * @param array $globalData
     * @access public
     * @return void
     */
    public function __construct(Dispatcher $events, XMLBuilder $builder, XSLTBridge $processor, array $globals = array())
    {
        $this->events      = $events;
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
    public function setGlobalData(array $data = array())
    {
        $this->globalData = array_merge_recursive($this->globalData, $data);
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
        $filename = pathinfo($file, PATHINFO_BASENAME);
        // We need to move the directory requested as the first search path
        // this stops conflicts. For example, with packages
        $path = pathinfo($file, PATHINFO_DIRNAME);
        $paths[] = $path;

        $this->processor->loadXSL($file);
        $this->processor->setParameters($this->getGlobalData());
        $this->dispatchBeforeResove($data);
        // Render template
        $this->builder->load($this->getData($data));
        return $this->processor->render($this->builder->createXML());
    }

    /**
     * dispatchBeforeResove
     *
     * @access protected
     * @return mixed
     */
    protected function dispatchBeforeResove(&$data)
    {
        $env = $data['__env'];
        $this->events->fire('xsltbridge.beforeresolve', array($this, $env));
        $data = array_merge($data, $env->getShared());
    }
}
