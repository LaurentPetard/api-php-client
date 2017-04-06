<?php

namespace Akeneo\Normalizer;

use Akeneo\Entities\ProductValue;

/**
 * Class ProductValueNormalizer
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductValueNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object)
    {
        return [
            'data' => $object->getData(),
            'locale' => $object->getLocale(),
            'scope' => $object->getChannel(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data)
    {
        return $data instanceof ProductValue;
    }
}
