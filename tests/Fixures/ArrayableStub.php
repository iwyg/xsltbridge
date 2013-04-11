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

class ArrayableStub extends ConvertToArrayStub
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function toArray()
    {
        return $this->data;
    }
}
