<?php

namespace Akeneo\Normalizer;

use Akeneo\Entities\Product;

/**
 * Class ProductNormalizer
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductNormalizer implements NormalizerInterface
{
    protected $productValueNormalizer;

    public function __construct(NormalizerInterface $productValueNormalizer)
    {
        $this->productValueNormalizer = $productValueNormalizer;
    }

    public function normalize($object)
    {
        return [
            'identifier' => $object->getIdentifier(),
            'family' => $object->getFamily(),
            'groups' => $object->getGroups(),
            'variant_group' => $object->getVariantGroup(),
            'categories' => $object->getCategories(),
            'enabled' => $object->isEnabled(),
            'values' => $this->normalizeProductValues($object->getProductValues()),
        ];
    }

    /**
     * @inheritdoc
     */
    public function supportsNormalization($data)
    {
        return $data instanceof Product;
    }

    /**
     * @param array $productValues
     *
     * @return array
     */
    protected function normalizeProductValues(array $productValues)
    {
        $normalizedProductValues = [];

        foreach ($productValues as $attribute => $attributeProductValues) {
            $normalizedProductValues[$attribute] = [];

            foreach ($attributeProductValues as $productValue) {
                $normalizedProductValues[$attribute][] = $this->productValueNormalizer->normalize($productValue);
            }
        }

        return $normalizedProductValues;
    }
}
