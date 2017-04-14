<?php

namespace Akeneo\Client;

use Akeneo\Authentication;
use Akeneo\Pagination\Factory\PageFactory;
use Akeneo\Pagination\Factory\PaginatorFactory;
use Akeneo\UrlGenerator;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;

/**
 * Class ClientBuilder
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ClientBuilder
{
    /** @var string */
    protected $baseUri;

    /** @var Authentication */
    protected $authentication;

    /** @var HttpClient */
    protected $httpClient;

    /** @var RequestFactory */
    protected $requestFactory;

    /** @var StreamFactory */
    protected $streamFactory;

    /**
     * @param string         $baseUri
     * @param Authentication $authentication
     */
    public function __construct($baseUri, Authentication $authentication, HttpClient $httpClient = null)
    {
        $this->baseUri = $baseUri;
        $this->authentication = $authentication;
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
    }

    /**
     * @return AkeneoPimClientInterface
     */
    public function build()
    {
        $resourceClient = $this->createResourceClient();
        $paginatorFactory = new PaginatorFactory($resourceClient, new PageFactory());
        $urlGenerator = new UrlGenerator($this->baseUri);

        return new AkeneoPimClient($urlGenerator, $resourceClient, $paginatorFactory);
    }

    protected function createResourceClient()
    {
        $requestFactory = MessageFactoryDiscovery::find();
        $streamFactory = StreamFactoryDiscovery::find();
        $urlGenerator = new UrlGenerator($this->baseUri);

        return new ResourceClient($this->authentication, $urlGenerator, $this->httpClient, $requestFactory, $streamFactory);
    }
}
