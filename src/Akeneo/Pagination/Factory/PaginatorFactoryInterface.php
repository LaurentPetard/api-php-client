<?php

namespace Akeneo\Pagination\Factory;

use Akeneo\Pagination\PaginatorInterface;

/**
 * Interface PaginatorFactoryInterface
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface PaginatorFactoryInterface
{
    /**
     * @param array        $firstPageData
     *
     * @return PaginatorInterface
     */
    public function createPaginator(array $firstPageData);
}
