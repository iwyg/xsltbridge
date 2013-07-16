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

use Illuminate\View\Environment;
use Illuminate\Support\Pluralizer;
use Thapp\XmlBuilder\XmlBuilder;
use Thapp\XsltBridge\Normalizer\EloquentAwareNormalizer as Normalizer;
use Thapp\XsltBridge\Engines\XslEngine;
use Illuminate\View\ViewServiceProvider;
use Illuminate\View\Engines\EngineResolver;

/**
 * Class: XsltServiceProvider
 *
 * @uses ViewServiceProvider
 *
 * @package
 * @version
 * @author Thomas Appel <mail@thomas-appel.com>
 * @license MIT
 */
class XsltServiceProvider extends ViewServiceProvider
{

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->app['config']->package('thapp/xsltbridge', dirname(dirname(__DIR__)) . '/config');
    }

    /**
     * register the Xsl view engine
     *
     * @access public
     * @return void
     */
    private function registerXslEngine()
    {
        $app = $this->app;
        $self = $this;

        $app['view']->addExtension('xsl', 'xsl', function () use ($app, $self)
        {
            $config = $app['config'];

            extract($config->get('xsltbridge::xsl'));
            extract($config->get('xsltbridge::xml'));

            $ignoredAttributes = $config->get('xsltbridge::normalizer.ignoredattributes', array());
            $ignoredObjects    = $config->get('xsltbridge::normalizer.ignoredobjects', array());
            $mappedAttributes  = $config->get('xsltbridge::attributes', array());
            $globals           = $config->get('xsltbridge::params', array());

            $bridge = new XsltBridge($app, new \XSLTProcessor, $app['events']);

            if (true === $phpfunctions) {
                $bridge->registerFunctions();
            }

            if (true === $profiling) {
                $dir = app_path() . DIRECTORY_SEPARATOR . $profilingdir;

                if (!is_dir($dir)) {
                    $app['files']->makeDirectory($dir);
                }

                $bridge->enableProfiling(sprintf('%s/%s_%s', $dir, microtime(true), 'xslt_profile.txt'));
            }

            $normalizer = new Normalizer();
            $normalizer->setIgnoredAttributes($ignoredAttributes);
            $normalizer->setIgnoredObjects($ignoredObjects);

            $builder  = new XmlBuilder($rootname, $normalizer);

            $self->setPluralizer($builder);
            $self->setSingularizer($builder);


            $builder->setAttributeMapp($mappedAttributes);
            $builder->setEncoding($encoding);

            $engine = new XslEngine($builder, $bridge, $globals);
            $engine->setEventDispatcher($app['events']);

            $app['events']->fire('xsltbridge.addparameters', array($engine));

            return $engine;
        });
    }

    /**
     * setPluralizer
     *
     * @access private
     * @return mixed
     */
    private function setPluralizer(XmlBuilder $builder)
    {
        $builder->setSingularizer(function ($value)
        {
            return Pluralizer::plural($value);
        });
    }

    /**
     * setSingularizer
     *
     * @access private
     * @return mixed
     */
    private function setSingularizer(XmlBuilder $builder)
    {
        $builder->setSingularizer(function ($value)
        {
            return Pluralizer::singular($value);
        });
    }


    /**
     * @todo register events
     * register the package
     */
    public function boot()
    {
        $this->package('thapp/xsltbridge');
        $this->registerXslEngine();
    }
}
