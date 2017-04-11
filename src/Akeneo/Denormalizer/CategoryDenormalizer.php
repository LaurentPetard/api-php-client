<?php

namespace Akeneo\Denormalizer;

use Akeneo\Entities\Category;

/**
 * Class CategoryDenormalizer
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CategoryDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     *
     * @return Category
     */
    public function denormalize($data, $type)
    {
        return new Category($data);
    }

    /**
     * @param $type
     *
     * @return bool
     */
    public function supportsNormalization($type)
    {
        return Category::class === $type;
    }
}
