<?php

namespace Akeneo\Pagination;

/**
 * Class Page
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Page
{
    /** @var string */
    protected $selfLink;

    /** @var string */
    protected $firstLink;

    /** @var string */
    protected $previousLink;

    /** @var string */
    protected $nextLink;

    /** @var string */
    protected $pageNumber;

    /** @var array */
    protected $items;

    /**
     * @param string $selfLink
     * @param string $firstLink
     * @param string $previousLink
     * @param string $nextLink
     * @param string $pageNumber
     * @param array  $items
     */
    public function __construct($selfLink, $firstLink, $previousLink, $nextLink, $pageNumber, array $items)
    {
        $this->selfLink = $selfLink;
        $this->firstLink = $firstLink;
        $this->previousLink = $previousLink;
        $this->nextLink = $nextLink;
        $this->pageNumber = $pageNumber;
        $this->items = $items;
    }

    /**
     * @return string
     */
    public function getSelfLink()
    {
        return $this->selfLink;
    }

    /**
     * @return string
     */
    public function getPreviousLink()
    {
        return $this->previousLink;
    }

    /**
     * @return string
     */
    public function getFirstLink()
    {
        return $this->firstLink;
    }

    /**
     * @return string
     */
    public function getNextLink()
    {
        return $this->nextLink;
    }

    /**
     * @return string
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }
}
