<?php

namespace Akeneo\Client;

use Akeneo\Route;

/**
 * This client class allows to request the API with OOP style.
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AkeneoPimClient implements AkeneoPimClientInterface
{
    /** @var AkeneoPimClientInterface */
    protected $client;

    protected $categoryNormalizer;

    protected $categoryDenormalizer;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
        $this->categoryDenormalizer = $client;
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
        return $this->resourceClient->getListResources(Route::CATEGORIES, $parameters);
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
        return $this->resourceClient->getListResources(Route::PRODUCTS, $options);
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
        return $this->resourceClient->getListResources(Route::MEDIA_FILES, $options);
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
}
