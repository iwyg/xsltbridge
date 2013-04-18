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
use Thapp\XsltBridge\Normalizer;
use Thapp\XsltBridge\XmlBuilder;

/**
 * Class: XmlBuilderTest
 *
 * @uses \PHPUnit_Framework_TestCase
 *
 * @package
 * @version
 * @author Thomas Appel <mail@thomas-appel.com>
 * @license MIT
 */
class XmlBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * setUp
     *
     * @access protected
     * @return mixed
     */
    protected function setUp()
    {
        $normalizer = new Normalizer;
        $this->builder = new XmlBuilder('data', $normalizer);
    }

    /**
     * tearDown
     *
     * @access protected
     * @return mixed
     */
    protected function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function testBuildXML()
    {
        $str  = '<data><foo>bar</foo></data>';
        $data = array('foo' => 'bar');
        $this->builder->load($data);
        $xml  = $this->builder->createXML(true);

        $this->assertXmlStringEqualsXmlString($str, $xml);
    }

    /**
     * @test
     */
    public function testBuildXMLSetAttributes()
    {
        $str  = '<data foo="bar"/>';
        $data = array('foo' => 'bar');
        $this->builder->load($data);
        $this->builder->setAttributeMapp(array('data' => array('foo')));
        $xml  = $this->builder->createXML(true);

        $this->assertXmlStringEqualsXmlString($str, $xml);
    }

    /**
     * @test
     */
    public function testBuildXMLSetNullData()
    {
        $str  = '<data/>';
        $xml  = $this->builder->createXML(true);
        $this->assertXmlStringEqualsXmlString($str, $xml);
    }

    /**
     * @test
     */
    public function testBuildXMLSetAttributesWithPrefix()
    {
        $str  = '<data foo="bar"/>';
        $data = array('@foo' => 'bar');
        $this->builder->load($data);
        $xml  = $this->builder->createXML(true);

        $this->assertXmlStringEqualsXmlString($str, $xml);
    }

    /**
     * @test
     */
    public function testBuildXMLCreateArray()
    {
        $str  = '<data><entries>a</entries><entries>b</entries><entries>c</entries></data>';
        $data = array('entries' => array('a', 'b', 'c'));
        $this->builder->load($data);

        $xml  = $this->builder->createXML(true);
        $this->assertXmlStringEqualsXmlString($str, $xml);
    }

    /**
     * @test
     */
    public function testBuildXMLCreateArrayAndSingularizeNodeNames()
    {
        $str  = '<data><entry>a</entry><entry>b</entry><entry>c</entry></data>';
        $data = array('entries' => array('a', 'b', 'c'));
        $this->builder->load($data);

        $this->builder->setSingularizer(function ($value) {
            return 'entry';
        });

        $xml  = $this->builder->createXML(true);
        $this->assertXmlStringEqualsXmlString($str, $xml);
    }
}
