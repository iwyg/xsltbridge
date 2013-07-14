<?php

/**
 * This File is part of the Thapp\XsltBridge\Normalizer package
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
 * @class EloquentAwareNormalizer
 * @package
 * @version $Id$
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
        if ($data instanceof Illuminate\Support\Contracts\ArrayableInterface) {
            return $data->toArray();
        }
        return parent::ensureArray($data);
    }
}
