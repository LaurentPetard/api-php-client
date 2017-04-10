<?php

namespace Akeneo\Client;

use Akeneo\Pagination\Factory\PaginatorFactoryInterface;
use Akeneo\Route;

/**
 * Class AkeneoPimClient
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AkeneoPimClient implements AkeneoPimClientInterface
{
    /** @var ResourceClient */
    protected $resourceClient;

    /** @var  PaginatorFactoryInterface */
    protected $paginatorFactory;

    /**
     * @param ResourceClient            $resourceClient
     * @param PaginatorFactoryInterface $paginatorFactory
     */
    public function __construct(ResourceClient $resourceClient, PaginatorFactoryInterface $paginatorFactory)
    {
        $this->resourceClient = $resourceClient;
        $this->paginatorFactory = $paginatorFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategory($code)
    {
        $url = sprintf(Route::CATEGORY, urlencode($code));

        return $this->resourceClient->getResource($url);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories(array $parameters = [])
    {
        return $this->getPaginatedResources(Route::CATEGORIES, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function createCategory(array $data)
    {
        $this->resourceClient->createResource(Route::CATEGORIES, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateCategory($code, array $data)
    {
        $url = sprintf(Route::CATEGORY, urlencode($code));
        $this->resourceClient->partialUpdateResource($url, $data);
    }

    public function partialUpdateCategories(array $data)
    {
        $this->resourceClient->partialUpdateResources(Route::CATEGORIES, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($code)
    {
        $url = sprintf(Route::ATTRIBUTE, urlencode($code));

        return $this->resourceClient->getResource($url);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(array $options = [])
    {
        return $this->getPaginatedResources(Route::ATTRIBUTES, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function createAttribute(array $data)
    {
        $this->resourceClient->createResource(Route::ATTRIBUTES, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateAttribute($code, array $data)
    {
        $url = sprintf(Route::ATTRIBUTE, urlencode($code));

        $this->resourceClient->partialUpdateResource($url, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct($code)
    {
        $url = sprintf(Route::PRODUCT, urlencode($code));

        return $this->resourceClient->getResource($url);
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts(array $options = [])
    {
        return $this->getPaginatedResources(Route::PRODUCTS, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function createProduct(array $data)
    {
        $this->resourceClient->createResource(Route::PRODUCTS, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function partialUpdateProduct($code, array $data)
    {
        $url = sprintf(Route::PRODUCT, $code);

        $this->resourceClient->partialUpdateResource($url, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaFile($code)
    {
        $url = sprintf(Route::MEDIA_FILE, $code);

        return $this->resourceClient->getResource($url);
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaFiles(array $options)
    {
        return $this->getPaginatedResources(Route::MEDIA_FILES, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function downloadMediaFile($code, $targetFilePath)
    {
        $url = sprintf(Route::MEDIA_FILE_DOWNLOAD, $code);

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
                'contents' => \GuzzleHttp\json_encode($mediaProductData),
            ],
            [
                'name' => 'file',
                // TODO : check file
                'contents' => fopen($sourceFilePath, 'r'),
            ]
        ];

        $this->resourceClient->performMultipartRequest(Route::MEDIA_FILES, $requestData);
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return \Akeneo\Pagination\PaginatorInterface
     */
    protected function getPaginatedResources($url, array $options)
    {
        $resourcesData = $this->resourceClient->getResource($url, $options);

        return $this->paginatorFactory->createPaginator($resourcesData);
    }
}
