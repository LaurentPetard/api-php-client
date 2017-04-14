<?php

namespace Akeneo\Client;

use Akeneo\Pagination\Factory\PaginatorFactoryInterface;
use Akeneo\Route;
use Akeneo\UrlGenerator;

/**
 * Class AkeneoPimClient
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AkeneoPimClient implements AkeneoPimClientInterface
{
    protected $baseUri;

    /** @var ResourceClient */
    protected $resourceClient;

    /** @var  PaginatorFactoryInterface */
    protected $paginatorFactory;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /**
     * @param UrlGenerator              $urlGenerator
     * @param ResourceClient            $resourceClient
     * @param PaginatorFactoryInterface $paginatorFactory
     */
    public function __construct(UrlGenerator $urlGenerator, ResourceClient $resourceClient, PaginatorFactoryInterface $paginatorFactory)
    {
        $this->urlGenerator = $urlGenerator;
        $this->resourceClient = $resourceClient;
        $this->paginatorFactory = $paginatorFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategory($code)
    {
        $url = $this->urlGenerator->generate(Route::CATEGORY, [$code]);

        return $this->resourceClient->getResource($url);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories(array $parameters = [])
    {
        $url = $this->urlGenerator->generate(Route::CATEGORIES, [], $parameters);

        return $this->getPaginatedResources($url);
    }

    /**
     * {@inheritdoc}
     */
    public function createCategory(array $data)
    {
        $url = $this->urlGenerator->generate(Route::CATEGORIES);

        $this->resourceClient->createResource($url, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateCategory($code, array $data)
    {
        $url = $this->urlGenerator->generate(Route::CATEGORY, [$code]);

        $this->resourceClient->partialUpdateResource($url, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateCategories($data)
    {
        $url = $this->urlGenerator->generate(Route::CATEGORIES);

        $this->resourceClient->partialUpdateResources($url, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($code)
    {
        $url = $this->urlGenerator->generate(Route::ATTRIBUTE, [$code]);

        return $this->resourceClient->getResource($url);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(array $parameters = [])
    {
        $url = $this->urlGenerator->generate(Route::ATTRIBUTES, [], $parameters);

        return $this->getPaginatedResources($url);
    }

    /**
     * {@inheritdoc}
     */
    public function createAttribute(array $data)
    {
        $url = $this->urlGenerator->generate(Route::ATTRIBUTES);

        $this->resourceClient->createResource($url, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateAttribute($code, array $data)
    {
        $url = $this->urlGenerator->generate(Route::ATTRIBUTE, [$code]);

        $this->resourceClient->partialUpdateResource($url, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct($code)
    {
        $url = $this->urlGenerator->generate(Route::PRODUCT, [$code]);

        return $this->resourceClient->getResource($url);
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts(array $options = [])
    {
        $url = $this->urlGenerator->generate(Route::PRODUCTS);

        return $this->getPaginatedResources($url, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function createProduct(array $data)
    {
        $url = $this->urlGenerator->generate(Route::PRODUCTS);

        $this->resourceClient->createResource($url, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateProduct($code, array $data)
    {
        $url = $this->urlGenerator->generate(Route::PRODUCT, [$code]);

        $this->resourceClient->partialUpdateResource($url, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaFile($code)
    {
        $url = $this->urlGenerator->generate(Route::MEDIA_FILE, [$code]);

        return $this->resourceClient->getResource($url);
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaFiles(array $options)
    {
        $url = $this->urlGenerator->generate(Route::MEDIA_FILES, [], $options);

        return $this->getPaginatedResources($url);
    }

    /**
     * {@inheritdoc}
     */
    public function downloadMediaFile($code, $targetFilePath)
    {
        $url = $this->urlGenerator->generate(Route::MEDIA_FILE, [$code]);

        $this->resourceClient->downloadResource($url, $targetFilePath);
    }

    /**
     * {@inheritdoc}
     */
    public function createMediaFile($sourceFilePath, array $mediaProductData)
    {
        $requestData = [
            [
                'name' => 'product',
                'contents' => json_encode($mediaProductData),
            ],
            [
                'name' => 'file',
                // TODO : check file
                'contents' => fopen($sourceFilePath, 'r'),
            ]
        ];

        $url = $this->urlGenerator->generate(Route::MEDIA_FILES);

        $this->resourceClient->sendMultipartRequest($url, $requestData);
    }

    /**
     * @param string $url
     *
     * @return \Akeneo\Pagination\PaginatorInterface
     */
    protected function getPaginatedResources($url)
    {
        $resourcesData = $this->resourceClient->getResource($url);

        return $this->paginatorFactory->createPaginator($resourcesData);
    }
}
