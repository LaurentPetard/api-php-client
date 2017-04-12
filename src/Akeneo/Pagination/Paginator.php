<?php

namespace Akeneo\Pagination;

use Akeneo\Client\ClientInterface;
use Akeneo\Client\ResourceClient;
use Akeneo\Pagination\Factory\PageFactoryInterface;

/**
 * Class Paginator
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Paginator implements PaginatorInterface
{
    /** @var ClientInterface */
    protected $client;

    /** @var Page */
    protected $currentPage;

    /** @var Page */
    protected $firstPage;

    /** @var int */
    protected $currentIndex;

    /** @var int */
    protected $totalIndex;

    /** @var PageFactoryInterface */
    protected $pageFactory;

    /**
     * @param ResourceClient       $client
     * @param PageFactoryInterface $pageFactory
     * @param Page                 $firstPage
     */
    public function __construct(ResourceClient $client, PageFactoryInterface $pageFactory, Page $firstPage)
    {
        $this->client = $client;
        $this->currentPage = $firstPage;
        $this->firstPage = $firstPage;
        $this->currentIndex = 0;
        $this->totalIndex = 0;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->currentPage->getItems()[$this->currentIndex];
    }

    /**
     * @return mixed
     */
    public function next()
    {
        $this->currentIndex++;
        $this->totalIndex++;

        $items = $this->currentPage->getItems();

        if (!isset($items[$this->currentIndex]) && $this->hasNextPage()) {
            $this->currentIndex = 0;
            $this->currentPage = $this->getNextPage();
        }
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->totalIndex;
    }

    /**
     * @return mixed
     */
    public function valid()
    {
        return isset($this->currentPage->getItems()[$this->currentIndex]);
    }

    /**
     * @return mixed
     */
    public function rewind()
    {
        $this->totalIndex = 0;
        $this->currentIndex = 0;
        $this->currentPage = $this->firstPage;
    }

    /**
     * @return Page
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @return Page
     */
    protected function hasNextPage()
    {
        return null !== $this->currentPage->getNextLink();
    }

    /**
     * Return the next page.
     */
    protected function getNextPage()
    {
        $nextPageData = $this->client->getResource($this->currentPage->getNextLink());
        $nextPageNumber = $this->currentPage->getPageNumber() + 1;

        return $this->pageFactory->createPage($nextPageData, $nextPageNumber);
    }
}
