<?php

namespace Akeneo\Pagination;

use Akeneo\Client\ClientInterface;
use Akeneo\HttpMethod;

/**
 * Class Paginator
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Paginator implements \Iterator
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

    /**
     * @param ClientInterface $client
     * @param Page            $currentPage
     */
    public function __construct(ClientInterface $client, Page $currentPage)
    {
        $this->client = $client;
        $this->currentPage = $currentPage;
        $this->firstPage = $currentPage;
        $this->currentIndex = 0;
        $this->totalIndex = 0;
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
            $response = $this->client->request(HttpMethod::GET, $this->currentPage->getNextLink());
            $body = json_decode($response->getBody()->getContents(), true);

            $nextLink = isset($body['_links']['next']['href']) ? $body['_links']['next']['href'] : null;
            $previousLink = isset($body['_links']['previous']['href']) ? $body['_links']['previous']['href'] : null;
            $selfLink= $body['_links']['self']['href'];
            $firstLink= $body['_links']['first']['href'];
            $items = $body['_embedded']['items'];

            $pageNumber = $this->getCurrentPage()->getPageNumber() + 1;

            $this->currentIndex = 0;
            $this->currentPage = new Page($selfLink, $firstLink, $previousLink, $nextLink, $pageNumber, $items);
        }
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->currentIndex;
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
}
