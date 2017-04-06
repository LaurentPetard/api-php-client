<?php

namespace Akeneo\Denormalizer;

/**
 * Defines the interface of denormalizers.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface DenormalizerInterface
{
    /**
     * Denormalizes an object into a set of arrays/scalars.
     *
     * @param array  $data data to denormalize
     * @param string $type type of the data to denormalize
     *
     * @return object
     */
    public function denormalize($data, $type);

    /**
     * Checks whether the given class is supported for denormalization by this denormalizer.
     *
     * @param mixed $data data to denormalize
     *
     * @return bool
     */
    public function supportsNormalization($type);
}
