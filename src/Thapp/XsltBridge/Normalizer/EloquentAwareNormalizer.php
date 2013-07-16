<?php

/**
 * This File is part of the Thapp\XsltBridge package
 *
 * (c) Thomas Appel <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Thapp\XsltBridge\Normalizer;

use Thapp\XmlBuilder\Normalizer;
use Illuminate\Support\Contracts\ArrayableInterface;

/**
 * Class: EloquentAwareNormalizer
 *
 * @uses Normalizer
 *
 * @package
 * @version
 * @author Thomas Appel <mail@thomas-appel.com>
 * @license MIT
 */
class EloquentAwareNormalizer extends Normalizer
{

    /**
     * check for arrayable interface
     *
     * {@inheritdoc}
     */
    public function ensureArray($data)
    {
        if ($this->isArrayableInterface($data)) {
            return $data->toArray();
        }

        return parent::ensureArray($data);
    }

    /**
     * isArrayAble
     *
     * @param  mixed $reflection a reflection object
     * @access protected
     * @return boolean
     */
    protected function isArrayable($data)
    {
        return $data->implementsInterface('Illuminate\Support\Contracts\ArrayableInterface');
    }

    /**
     * isArrayableInterface
     *
     * @param mixed $data
     * @access private
     * @return boolean
     */
    private function isArrayableInterface($data)
    {
        return $data instanceof ArrayableInterface;
    }
}
