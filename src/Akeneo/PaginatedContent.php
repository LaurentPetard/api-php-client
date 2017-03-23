<?php

namespace Akeneo;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PaginatedContent
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class PaginatedContent implements \Iterator
{
    /** @var string */
    protected $selfLink;

    /** @var string */
    protected $previousLink;

    /** @var string */
    protected $firstLink;

    /** @var string */
    protected $nextLink;

    /** @var string */
    protected $page;

    /** @var array */
    protected $content;

    /**
     * @param string $selfLink
     * @param string $previousLink
     * @param string $firstLink
     * @param string $nextLink
     * @param array  $content
     */
    public function __construct($selfLink, $previousLink, $firstLink, $nextLink, array $content)
    {
        $this->selfLink = $selfLink;
        $this->previousLink = $previousLink;
        $this->firstLink = $firstLink;
        $this->nextLink = $nextLink;
        $this->content = $content;
        $this->page = 1;
    }

}
