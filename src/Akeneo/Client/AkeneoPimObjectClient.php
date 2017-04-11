<?php

namespace Akeneo\Client;

use Akeneo\Denormalizer\DenormalizerInterface;
use Akeneo\Entities\MediaFile;
use Akeneo\Normalizer\NormalizerInterface;
use Akeneo\Entities\Category;
use Akeneo\Entities\Product;
use Akeneo\Pagination\Factory\PaginatorFactoryInterface;
use Akeneo\Pagination\PaginatorInterface;
use Akeneo\Route;

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
    protected $resourceClient;

    /** @var NormalizerInterface */
    protected $normalizer;

    /** @var DenormalizerInterface */
    protected $denormalizer;

    /** @var PaginatorFactoryInterface */
    protected $paginatorFactory;

    /**
     * @param ResourceClient            $client
     * @param PaginatorFactoryInterface $paginatorFactory
     * @param NormalizerInterface       $normalizer
     * @param DenormalizerInterface     $denormalizer
     */
    public function __construct(
        ResourceClient $client,
        PaginatorFactoryInterface $paginatorFactory,
        NormalizerInterface $normalizer,
        DenormalizerInterface $denormalizer
    ) {
        $this->resourceClient = $client;
        $this->paginatorFactory = $paginatorFactory;
        $this->normalizer = $normalizer;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @param $code
     *
     * @return Category
     */
    public function getCategory($code)
    {
        $url = sprintf(Route::CATEGORY, urlencode($code));
        $category = $this->resourceClient->getResource($url);

        return $this->denormalizer->denormalize($category, Category::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories(array $parameters = [])
    {
        return $this->getPaginatedResources(Route::CATEGORIES, $parameters, Category::class);
    }

    /**
     * {@inheritdoc}
     */
    public function createCategory(Category $category)
    {
        $this->resourceClient->createResource(Route::CATEGORIES, $category->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateCategory(Category $category)
    {
        $url = sprintf(Route::CATEGORY, urlencode($category->getCode()));

        $this->resourceClient->partialUpdateResource($url, $category->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateCategories(array $categories)
    {
        $normalizedCategories = [];
        foreach ($categories as $category) {
            $normalizedCategories[] = $category->toArray();
        }

        $this->resourceClient->partialUpdateResources(Route::CATEGORIES, $normalizedCategories);
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct($identifier)
    {
        $url = sprintf(Route::PRODUCT, urlencode($identifier));
        $product = $this->resourceClient->getResource($url);

        return $this->denormalizer->denormalize($product, Product::class);
    }

    public function createProduct(Product $product)
    {
        $this->resourceClient->createResource(Route::PRODUCTS, $product->toArray());
    }

    public function partialUpdateProduct(Product $product)
    {
        $url = sprintf(Route::PRODUCT, $product->getIdentifier());

        $this->resourceClient->partialUpdateResource($url, $product->toArray());
    }

    /**
     * @param $code
     *
     * @return MediaFile
     */
    public function getMediaFile($code)
    {
        $url = sprintf(Route::MEDIA_FILE, $code);
        $mediaFile = $this->resourceClient->getResource($url);

        return $this->denormalizer->denormalize($mediaFile, MediaFile::class);
    }

    /**
     * @param string $url
     * @param array  $options
     * @param string $entityType
     *
     * @return PaginatorInterface
     */
    protected function getPaginatedResources($url, array $options, $entityType)
    {
        $resourcesData = $this->resourceClient->getResource($url, $options);

        return $this->paginatorFactory->createPaginator($resourcesData, $entityType);
    }
}
