<?php

namespace Akeneo\Client;

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
     * @return mixed
     */
    public function getCategory($code);

    /**
     * @param array $options
     *
     * @throws Exception
     *
     * @return mixed
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
     * @param string $code
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function getAttribute($code);

    /**
     * @param array $options
     *
     * @throws Exception
     *
     * @return mixed
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
     * @param string $code
     * @param array $filters
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function getProduct($code, array $filters);

}
