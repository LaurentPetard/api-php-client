<?php

namespace Akeneo\Client;

use Akeneo\Entities\Product;
use Akeneo\Pagination\Factory\PaginatorFactory;
use Akeneo\Pagination\PaginatorInterface;

/**
 * Interface AkeneoPimClientInterface
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface AkeneoPimClientInterface
{
    /**
     * @param string $code
     *
     * @throws Exception
     *
     * @return array
     */
    public function getCategory($code);

    /**
     * @param array $options
     *
     * @throws Exception
     *
     * @return PaginatorInterface
     */
    public function getCategories(array $options = []);

    /**
     * @param array $data
     *
     * @throws Exception
     */
    public function createCategory(array $data);

    /**
     * @param string $code
     * @param array  $data
     *
     * @throws Exception
     */
    public function partialUpdateCategory($code, array $data);

    /**
     * @param array|\Traversable $data
     *
     * @throws Exception
     */
    public function partialUpdateCategories($data);

    /**
     * @param string $code
     *
     * @throws Exception
     *
     * @return array
     */
    public function getAttribute($code);

    /**
     * @param array $options
     *
     * @throws Exception
     *
     * @return PaginatorInterface
     */
    public function getAttributes(array $options = []);

    /**
     * @param array $data
     *
     * @throws Exception
     */
    public function createAttribute(array $data);

    /**
     * @param string $code
     * @param array  $data
     *
     * @throws Exception
     */
    public function partialUpdateAttribute($code, array $data);

    /**
     * @param string $identifier
     *
     * @throws Exception
     *
     * @return array
     */
    public function getProduct($identifier);

    /**
     * @param array $options
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function getProducts(array $options);

    /**
     * @param array $data
     *
     * @throws Exception
     */
    public function createProduct(array $data);

    /**
     * @param string $code
     * @param array  $data
     *
     * @throws Exception
     */
    public function partialUpdateProduct($code, array $data);

    /**
     * @param string $code
     *
     * @throws Exception
     *
     * @return array
     */
    public function getMediaFile($code);

    /**
     * @param array $options
     *
     * @throws Exception
     *
     * @return PaginatorInterface
     */
    public function getMediaFiles(array $options);

    /**
     * @param string $code
     * @param string $targetTargetFilePath
     *
     * @throws Exception
     */
    public function downloadMediaFile($code, $targetTargetFilePath);

    /**
     * @param string $sourceFilePath
     * @param array  $mediaProductData
     *
     * @throws Exception
     */
    public function createMediaFile($sourceFilePath, array $mediaProductData);

}
