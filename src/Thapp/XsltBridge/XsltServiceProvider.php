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
use Thapp\XsltBridge\Engines\XslEngine;
use Illuminate\View\ViewServiceProvider;
use Illuminate\View\Engines\EngineResolver;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

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
        $path = realpath(dirname(__DIR__) . '/../config');
        $this->app['config']->package('thapp/xsltbridge', $path);
        $this->registerEngineResolver();
        $this->registerEnvironment();
    }

    /**
     * {@inheritDoc}
     */
    public function registerEngineResolver()
    {
        $service = $this;
        $this->app['view.engine.resolver'] = $this->app->share(function ($app) use ($service)
        {
            $resolver = new EngineResolver;
            foreach (array('php', 'blade', 'xsl') as $engine) {
                $service->{'register' . ucfirst($engine) . 'Engine'}($resolver);
            }
            return $resolver;
        });
    }

    /**
     * registerXslEngine
     *
     * @param mixed $resolver
     * @access public
     * @return void
     */
    public function registerXslEngine($resolver)
    {
        $app = $this->app;

        $resolver->register('xsl', function () use ($app, $resolver)
        {
            $config = $app['config'];

            $registerFunctions = $config->get('xsltbridge::xsl.phpfunctions', false);
            $enableProfiling   = $config->get('xsltbridge::xsl.profiling', false);
            $rootname          = $config->get('xsltbridge::xml.rootname', 'data');
            $encoding          = $config->get('xsltbridge::xml.encoding', 'UTF-8');
            $ignoredAttributes = $config->get('xsltbridge::normalizer.ignoredattributes', array());
            $mappedAttributes  = $config->get('xsltbridge::attributes', array());
            $globals           = $config->get('xsltbridge::params', array());

            $bridge = new XsltBridge($app, new \XSLTProcessor, $app['events']);

            if (true === $registerFunctions) {
                $bridge->registerFunctions();
            }

            if (true === $enableProfiling) {
                $dir = app_path() . DIRECTORY_SEPARATOR . $app['config']->get('xsltbridge::xsl.profilingdir', 'profile');

                if (!is_dir($dir)) {
                    $app['files']->makeDirectory($dir);
                }

                $bridge->enableProfiling(sprintf('%s/%s_%s', $dir, microtime(true), 'xslt_profile.txt'));
            }

            $normalizer = new Normalizer();
            $normalizer->setIgnoredAttributes($ignoredAttributes);

            $builder  = new XMLBuilder($rootname, $normalizer);
            // set the singularizer on the xml builder
            $builder->setSingularizer(function ($value)
            {
                return Pluralizer::singular($value);
            });

            $builder->setAttributeMapp($mappedAttributes);
            $builder->setEncoding($encoding);

            //$app->instance(__NAMESPACE__  . '\XMLBuilder', $builder);
            //$app->instance(__NAMESPACE__  . '\XsltBridge', $bridge);

            //$engine = $app->make(__NAMESPACE__ . '\Engines\XslEngine');

            //var_dump($engine);

            return new XslEngine($app['events'], $builder, $bridge, $globals);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function registerEnvironment()
    {
        $fileExtensions = explode(',', $this->app['config']->get('xsltbridge::extension', 'xsl'));

        foreach ($fileExtensions as $extension) {
            $this->app['view']->addExtension($extension, 'xsl');
        }
    }
}
