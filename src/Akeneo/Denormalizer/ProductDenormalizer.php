<?php

namespace Akeneo\Denormalizer;

use Akeneo\Entities\Category;
use Akeneo\Entities\Product;

/**
 * Class ProductDenormalizer
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     *
     * @return Product
     */
    public function denormalize($data, $type)
    {
        $product = new Product();
        $product
            ->setFamily($data['family'])
            ->setIdentifier($data['identifier'])
            ->setVariantGroup($data['variant_group']);

        if (true === $data['enabled']) {
            $product->enable();
        } else {
            $product->disable();
        }

        foreach($data['groups'] as $group) {
            $product->addGroup($group);
        }

        foreach($data['categories'] as $category) {
            $product->addCategory($category);
        }

        foreach ($data['values'] as $attributeCode => $productValues) {
            foreach ($productValues as $productValue) {
                $product->setProductValue(
                    $attributeCode,
                    $productValue['data'],
                    $productValue['locale'],
                    $productValue['scope']
                );
            }
        }

        return $product;
    }

    /**
     * @param $type
     *
     * @return bool
     */
    public function supportsNormalization($type)
    {
        return Product::class === $type;
    }
}
