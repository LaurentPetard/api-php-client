<?php

namespace Akeneo\Client;

use Akeneo\Authentication;
use Akeneo\Denormalizer\CategoryDenormalizer;
use Akeneo\Denormalizer\EntityDenormalizer;
use Akeneo\Denormalizer\MediaFileDenormalizer;
use Akeneo\Denormalizer\ProductDenormalizer;
use Akeneo\Normalizer\CategoryNormalizer;
use Akeneo\Normalizer\EntityNormalizer;
use Akeneo\Normalizer\ProductNormalizer;
use Akeneo\Normalizer\ProductValueNormalizer;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Akeneo\Pagination\Factory\PageFactory;
use Akeneo\Pagination\Factory\PaginatorFactory;

/**
 * Class ClientBuilder
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ClientBuilder
{
    const TIMEOUT = -1;

    /** @var string */
    protected $baseUri;

    /** @var Authentication */
    protected $authentication;

    /**
     * @param string         $baseUri
     * @param Authentication $authentication
     */
    public function __construct($baseUri, Authentication $authentication)
    {
        $this->baseUri = $baseUri;
        $this->authentication = $authentication;
    }

    /**
     * @return AkeneoPimClientInterface
     */
    public function build()
    {
        $resourceClient = $this->createResourceClient();
        $paginatorFactory = new PaginatorFactory($resourceClient, new PageFactory());


        return new AkeneoPimClient($resourceClient, $paginatorFactory);
    }

    protected function createResourceClient()
    {
        $guzzleClient = new Client([
            'base_uri' => $this->baseUri,
            RequestOptions::TIMEOUT  => static::TIMEOUT,
        ]);

        return new ResourceClient($this->authentication, $guzzleClient);
    }
}
