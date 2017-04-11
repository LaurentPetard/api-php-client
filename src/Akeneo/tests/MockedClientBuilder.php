<?php

namespace Akeneo\tests;

use Akeneo\Authentication;
use Akeneo\Client\ClientBuilder;
use Akeneo\Client\ResourceClient;
use GuzzleHttp\ClientInterface;

/**
 * Class MockedClientBuilder
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MockedClientBuilder extends ClientBuilder
{
    /** @var ClientInterface */
    protected $guzzleClient;

    /**
     * @param string         $baseUri
     * @param Authentication $authentication
     * @param ClientInterface $guzzleClient
     */
    public function __construct($baseUri, Authentication $authentication, ClientInterface $guzzleClient)
    {
        parent::__construct($baseUri, $authentication);
        $this->guzzleClient = $guzzleClient;
    }

    protected function createResourceClient()
    {
        return new ResourceClient($this->authentication, $this->guzzleClient);
    }
}
