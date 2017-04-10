<?php

namespace Akeneo\Pagination\Factory;

use Akeneo\Client\ResourceClient;
use Akeneo\Pagination\Paginator;

/**
 * Class PaginatorFactory
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class PaginatorFactory implements PaginatorFactoryInterface
{
    /**
     * @var ResourceClient
     */
    protected $client;

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @param ResourceClient       $client
     * @param PageFactoryInterface $pageFactory
     */
    public function __construct(ResourceClient $client, PageFactoryInterface $pageFactory)
    {
        $this->client = $client;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @param array $firstPageData
     *
     * @return Paginator
     */
    public function createPaginator(array $firstPageData, $entityType = null)
    {
        $firstPage = $this->pageFactory->createPage($firstPageData, 1, $entityType);

        return new Paginator($this->client, $firstPage, $this->pageFactory, $entityType);
    }
}
