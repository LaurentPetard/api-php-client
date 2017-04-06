<?php

namespace Akeneo\Client;

use Akeneo\Entities\Category;
use Akeneo\Denormalizer\CategoryDenormalizer;
use Akeneo\Normalizer\CategoryNormalizer;

/**
 * This client class allows to request the API with OOP style.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AkeneoPimObjectClient
{
    /** @var AkeneoPimClientInterface */
    protected $client;

    protected $normalizer;

    protected $denormalizer;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
        $this->normalizer = new CategoryNormalizer();
        $this->denormalizer = new CategoryDenormalizer();
    }

    /**
     * @param $code
     *
     * @return Category
     */
    public function getCategory($code)
    {
         $category = $this->client->getCategory($code);

         return $this->denormalizer->denormalize($category, Category::class);
    }

    ///**
    // * {@inheritdoc}
    // */
    //public function getCategories(array $parameters = [])
    //{
    //    return $this->resourceClient->getListResources(Route::CATEGORIES, $parameters);
    //}

    /**
     * {@inheritdoc}
     */
    public function createCategory(Category $category)
    {
        $category = $this->normalizer->normalize($category);

        $this->client->createCategory($category);
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateCategory(Category $category)
    {
        $category = $this->categoryNormalizer->normalize($category);

        $this->client->partialUpdateCategory($category);
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateCategories(array $categories)
    {
        $normalizedCategories = [];
        foreach ($categories as $category) {
            $normalizedCategories[] = $this->categoryNormalizer->normalize($category);
        }

        $this->client->partialUpdateCategory($normalizedCategories);
    }
}
