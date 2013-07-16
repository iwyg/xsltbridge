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

use \XSLTProcessor;
use \DOMDocument;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Application;

/**
 * Class: XsltBridge
 *
 * @package
 * @version
 * @author Thomas Appel <mail@thomas-appel.com>
 * @license MIT
 */
class XsltBridge
{
    /**
     * app
     *
     * @var Application
     */
    protected $app;

    /**
     * xsl
     *
     * @var \DOMDocument
     */
    protected $xsl;

    /**
     * processor
     *
     * @var \XSLTProcessor
     */
    protected $processor;

    /**
     * registeredFunctions
     *
     * @var array
     */
    protected $registeredFunctions = array();

    /**
     * event
     *
     * @var mixed
     */
    protected $event;

    /**
     * __construct
     *
     * @param Application $app
     * @access public
     * @return void
     */
    public function __construct(Application $app, XSLTProcessor $processor, Dispatcher $events)
    {
        $this->xsl       = new \DOMDocument();
        $this->app       = $app;
        $this->event     = $events;
        $this->processor = $processor;
    }

    /**
     * setParamter
     *
     * @param mixed $name
     * @param mixed $parameter
     * @param mixed $nsURL
     * @access public
     * @return mixed
     */
    public function setParameter($name, $parameter, $nsURL = null)
    {
        return $this->processor->setParameter($nsURL, $name, $parameter);
    }

    /**
     * setParamters
     *
     * @param mixed $name
     * @param array $parameters
     * @param mixed $nsURL
     * @access public
     * @return mixed
     */
    public function setParameters(array $parameters, $nsURL = null)
    {
        return $this->processor->setParameter($nsURL, $parameters);
    }

    /**
     * registerFunctions
     *
     * @param array $functions
     * @access public
     * @return void
     */
    public function registerFunctions(array $functions = null)
    {
        if (is_null($functions)) {
            return $this->processor->registerPHPFunctions();
        }
        return $this->processor->registerPHPFunctions($functions);
    }

    /**
     * enableProfiling
     *
     * @param mixed $file
     * @access public
     * @return bool
     */
    public function enableProfiling($file)
    {
        return $this->processor->setProfiling($file);
    }

    /**
     * registerFunction
     *
     * @param mixed $name
     * @param mixed $function
     * @access public
     * @return void
     */
    public function registerFunction($name, $function)
    {
        $this->processor->registerPHPFunctions($function);
    }

    /**
     * loadXSL
     *
     * @param string $xslfile path to a xsl file
     * @access public
     * @return void
     */
    public function loadXSL($xslfile)
    {
        return $this->xsl->load($xslfile);
    }

    /**
     * parse
     *
     * @param DOMDocument $xml
     * @access public
     * @return mixed
     */
    public function render(DOMDocument $xml)
    {
        $this->processor->importStyleSheet($this->xsl);
        $rendered = $this->processor->transformToXML($xml);

        return $rendered;
    }
}
