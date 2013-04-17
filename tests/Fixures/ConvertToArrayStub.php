<?php

/**
 * This File is part of the vendor\thapp\tests\Fixures package
 *
 * (c) Thomas Appel <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Thapp\XsltBridge\Tests\Stubs;

/**
 * @class ConvertToArrayStub
 */

class ConvertToArrayStub
{
    /**
     * foo
     *
     * @var string
     */
    protected $foo = 'foo';

    /**
     * bar
     *
     * @var string
     */
    protected $bar = 'bar';

    /**
     * getFoo
     *
     * @param mixed $param
     * @access public
     * @return mixed
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * getFoo
     *
     * @param mixed $param
     * @access public
     * @return mixed
     */
    public function getFoo()
    {
        return $this->foo;
    }

    public function getAttributes($param)
    {
        return array('attributes');
    }

    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    public function setFoo($foo)
    {
        $this->foo = $foo;
    }


}
