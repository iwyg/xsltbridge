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
        $this->app['config']->package('tapp/xsltbridge', $path);
        $this->registerEngineResolver();
        $this->registerEnvironment();
    }

    /**
     * {@inheritDoc}
     */
    public function registerEngineResolver()
    {
        $service = $this;
        $this->app['view.engine.resolver'] = $this->app->share(

            function ($app) use ($service)
            {
                $resolver = new EngineResolver;

                // Next we will register the various engines with the resolver so that the
                // environment can resolve the engines it needs for various views based
                // on the extension of view files. We call a method for each engines.
                foreach (array('php', 'blade', 'xsl') as $engine) {
                    $service->{'register' . ucfirst($engine) . 'Engine'}($resolver);
                }

                return $resolver;
            }
        );
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
            $bridge = new XsltBridge($app, new \XSLTProcessor, $app['events']);

            if (true === $app['config']->get('xsltbridge::xsl.phpfunctions', false)) {
                $bridge->registerFunctions();
            }

            if (true === $app['config']->get('xsltbridge::xsl.profiling', false)) {
                $dir = app_path() . DIRECTORY_SEPARATOR . $app['config']->get('xsltbridge::xsl.profilingdir', 'profile');

                if (!is_dir($dir)) {
                    $app['files']->makeDirectory($dir);
                }

                $bridge->enableProfiling($dir . '/xslt_profile.txt');
            }

            $globals  = $app['config']->get('xsltbridge::params', array());
            $rootname = $app['config']->get('xsltbridge::xml.rootname', 'data');

            $normalizer = new Normalizer();
            $normalizer->setIgnoredAttributes($app['config']->get('xsltbridge::normalizer.ignoredattributes', array()));

            $encoding = $app['config']->get('xsltbridge::xml.encoding', 'UTF-8');

            $builder  = new XMLBuilder($rootname, $normalizer);
            // set the singularizer on the xml builder
            $builder->setSingularizer(function ($value)
            {
                return Pluralizer::singular($value);
            });

            $builder->setAttributeMapp($app['config']->get('xsltbridge::attributes', array()));
            $builder->setEncoding($encoding);

            return new XslEngine($builder, $bridge, $globals);
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
