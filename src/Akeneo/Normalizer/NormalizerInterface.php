<?php

namespace Akeneo\Normalizer;

/**
 * Defines the interface of normalizers.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface NormalizerInterface
{
    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param object $object  object to normalize
     *
     * @return array|scalar
     */
    public function normalize($object);

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed  $data  object to normalize
     *
     * @return bool
     */
    public function supportsNormalization($data);
}
