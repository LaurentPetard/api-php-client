<?php

namespace Akeneo\Pagination\Factory;

/**
 * Interface PageFactoryInterface
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface PageFactoryInterface
{
    /**
     * @param array $data
     * @param int   $pageNumber
     *
     * @return Page
     */
    public function createPage(array $data, $pageNumber);
}
