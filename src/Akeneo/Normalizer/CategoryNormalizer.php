<?php

namespace Akeneo\Normalizer;

use Akeneo\Entities\Category;

/**
 * Class CategoryNormalizer
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CategoryNormalizer implements NormalizerInterface
{
    /**
     * @param Category $category
     *
     * @return mixed
     */
    public function normalize($category)
    {
        $data = [
            'code'   => $category->getCode(),
            'parent' => $category->getParent(),
            'labels' => $category->getLabels()
        ];

        return $data;
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function supportsNormalization($data)
    {
        return $data instanceof Category;
    }
}
