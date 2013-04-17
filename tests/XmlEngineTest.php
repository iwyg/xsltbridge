<?php

/**
 * This File is part of the Thapp\XsltBridge package
 *
 * (c) Thomas Appel <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Thapp\XsltBridge\Tests;

use Mockery as m;
use org\bovigo\vfs\VfsStream;
use Thapp\XsltBridge\XMLBuilder;
use Thapp\XsltBridge\XsltBridge;
use Illuminate\Events\Dispatcher;
use Thapp\XsltBridge\Engines\XslEngine;
use Illuminate\View\Engines\EngineInterface;

/**
 * @class XmlEngineTest
 */
class XmlEngineTest extends \PHPUnit_Framework_TestCase
{

    protected function tearDown()
    {
        m::close();
    }


    public function provider()
    {
        $file = __DIR__ . '/Fixures/test.xsl';

        return array(
            array(
                array('foo' => 'bar'),
                array('somexml' => 'view'),
                $file,
                new \DOMDocument,
                '<somehtml>view</somehtml>',
            ),
        );
    }

    /**
     * @test
     * @dataProvider provider
     */
    public function testGetRenderedView($globalData, $data, $file, $xml, $result)
    {
        $engine = call_user_func_array(array($this, 'createEngine'), func_get_args());
        $this->assertEquals($result, $engine->get($file, $data));
    }

    /**
     * @test
     * @dataProvider provider
     */
    public function testGetGlobalData($globalData, $data, $file, $xml, $result)
    {
        $engine = call_user_func_array(array($this, 'createEngine'), func_get_args());
        $this->assertEquals($globalData, $engine->getGlobalData());
        $engine->setGlobalData(array('foo' => 'baz'));
        $this->assertEquals(array('foo' => 'baz'), $engine->getGlobalData());
        $engine->setGlobalData(array('bar' => 'foo'));
        $this->assertEquals(array('foo' => 'baz', 'bar' => 'foo'), $engine->getGlobalData());
    }

    /**
     * @test
     * @dataProvider provider
     */
    public function testGetData($globalData, $data, $file, $xml, $result)
    {
        $engine = call_user_func_array(array($this, 'createEngine'), func_get_args());
        $this->assertEquals(array('params' => $globalData, 'somexml' => 'view'), $engine->getData($data));
    }

    /**
     * createEngine
     *
     * @param mixed $globalData
     * @param mixed $data
     * @param mixed $xsl
     * @param mixed $xml
     * @param mixed $result
     * @access protected
     * @return mixed
     */
    protected function createEngine($globalData, $data, $xsl, $xml, $result)
    {
        try {
            $app = m::mock('alias:Illuminate\Foundation\Application');
        } catch (\Exception $e) {}

        $builder   = m::mock('Thapp\XsltBridge\XmlBuilder');

        $builder->shouldReceive('load')->atMost(1);
        $builder->shouldReceive('createXML')->andReturn($xml);

        $processor = m::mock('Thapp\XsltBridge\XsltBridge');
        $processor->shouldReceive('loadXSL')->with($xsl);

        $processor->shouldReceive('setParameters');

        $processor->shouldReceive('render')->with($xml)->andReturn($result);

        return new XslEngine($builder, $processor, $globalData);
    }
}
