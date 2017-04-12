<?php

namespace Akeneo\tests;

use Akeneo\Authentication;
use Akeneo\Client\AkeneoPimClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;

/**
 * Client mocker aims to create a guzzle client with mock responses .
 * The history of sent requests is available as well.
 *
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ClientMocker
{
    /** @var array */
    protected $history = [];

    /** @var AkeneoPimClientInterface */
    protected $client;

    /**
     * @param string         $baseUri
     * @param Authentication $authentication
     * @param array          $mock
     */
    public function __construct($baseUri, Authentication $authentication, array $mock)
    {
        $guzzleClient = $this->createGuzzleClient($baseUri, $mock);
        $mockClientBuilder = new MockedClientBuilder($baseUri, $authentication, $guzzleClient);
        $this->client = $mockClientBuilder->build();
    }

    /**
     * @param string $baseUri
     * @param array  $mock
     *
     * @return Client
     */
    protected function createGuzzleClient($baseUri, array $mock)
    {
        $mockHandler = new MockHandler($mock);
        $handler = HandlerStack::create($mockHandler);

        $history = Middleware::history($this->history);
        $handler->push($history);

        $guzzleClient = new Client([
            'handler'               => $handler,
            'base_uri'              => $baseUri,
            RequestOptions::TIMEOUT => 1,
        ]);

        return $guzzleClient;
    }

    /**
     * @return AkeneoPimClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return array
     */
    public function getHistory()
    {
            foreach ($this->history as $message) {
                try {
                    $message['request']->getBody()->rewind();
                } catch (\RuntimeException $e) {

                }
                $message['response']->getBody()->rewind();
            }

        return $this->history;
    }
}
