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
        $category = new Category();
        $category
            ->setCode($data['code'])
            ->setParent($data['parent']);

        if (isset($data['_links'])) {
            foreach ($data['_links'] as $rel => $link) {
                $category->addLink($rel, $data['_links'][$rel]['href']);
            }
        }

        foreach ($data['labels'] as $locale => $label) {
            $category->addLabel($locale, $label);
        }

        return $category;
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
