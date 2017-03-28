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
     * @param array  $options
     *
     * @return mixed
     */
    public function getCategory($code, array $options = []);

    /**
     * @param array $options
     *
     * @return mixed
     */
    public function getCategories($options = []);
}
