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

/**
 * Class: NormalizerInterface
 *
 *
 * @package
 * @version
 * @author Thomas Appel <mail@thomas-appel.com>
 * @license MIT
 */
interface NormalizerInterface
{
    /**
     * normalize
     *
     * @param string $string
     * @access public
     * @return string
     */
    public function normalize($string);

    /**
     * ensureArray
     *
     * @param mixed $data
     * @access public
     * @return array
     */
    public function ensureArray($data);
}
