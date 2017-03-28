<?php

namespace Akeneo\Client;

/**
 * Interface ClientInterface
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ClientInterface
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
}
