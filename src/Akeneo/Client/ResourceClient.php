<?php

namespace Akeneo\Client;

use Akeneo\Authentication;
use Akeneo\HttpMethod;
use Akeneo\Pagination\Page;
use Akeneo\Pagination\Paginator;
use Akeneo\Route;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Client of Akeneo PIM
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class ResourceClient
{
    const TIMEOUT = 5.0;

    /* @var Authentication */
    protected $authentication;

    /** @var GuzzleClient */
    protected $guzzleClient;


    /**
     * @param                $baseUri
     * @param Authentication $authentication
     */
    public function __construct($baseUri, Authentication $authentication)
    {
        $this->authentication = $authentication;

        $this->guzzleClient = new GuzzleClient([
            'base_uri' => $baseUri,
            RequestOptions::TIMEOUT  => static::TIMEOUT,
        ]);
    }

    /**
     * @param string $url
     *
     * @throws Exception
     *
     * @return array
     */
    public function getResource($url)
    {
        $response = $this->performAuthenticatedRequest(HttpMethod::GET, $url);

        if (200 !== $response->getStatusCode()) {
            throw new Exception();
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $url
     * @param array  $data
     *
     * @throws Exception
     */
    public function createResource($url, array $data)
    {
        $response = $this->performAuthenticatedRequest(HttpMethod::POST, $url, [
            RequestOptions::HEADERS => ['Content-Type' => 'application/json'],
            RequestOptions::JSON    => $data,
        ]);
        if (201 !== $response->getStatusCode()) {
            throw new Exception();
        }
    }

    /**
     * @param string $url
     * @param array  $data
     *
     * @throws Exception
     */
    public function partialUpdateResource($url, array $data)
    {
        $response = $this->performAuthenticatedRequest(HttpMethod::PATCH, $url, [
            RequestOptions::HEADERS => ['Content-Type' => 'application/json'],
            RequestOptions::JSON    => $data,
        ]);

        if (204 !== $response->getStatusCode() && 201 !== $response->getStatusCode()) {
            throw new Exception($response->getStatusCode() . '--' . $response->getBody()->getContents());
        }
    }

    /**
     * @param string $url
     * @param array  $parameters
     *
     * @throws Exception
     *
     * @return Paginator
     */
    public function getListResources($url, array $parameters = [])
    {
        $options = [RequestOptions::QUERY => $parameters];

        $response =  $this->performAuthenticatedRequest(HttpMethod::GET, $url, $options);

        if (200 !== $response->getStatusCode()) {
            throw new Exception();
        }

        $body = json_decode($response->getBody()->getContents(), true);

        $nextLink = isset($body['_links']['next']['href']) ? $body['_links']['next']['href'] : null;
        $previousLink = isset($body['_links']['previous']['href']) ? $body['_links']['previous']['href'] : null;
        $selfLink= $body['_links']['self']['href'];
        $firstLink= $body['_links']['first']['href'];
        $items = $body['_embedded']['items'];

        $page = new Page($selfLink, $firstLink, $previousLink, $nextLink, 1, $items);

        return new Paginator($this, $page);
    }


    /**
     * @param string $httpMethod
     * @param string $url
     * @param array  $options
     *
     * @throws \Exception
     *
     * @return ResponseInterface
     */
    public function performAuthenticatedRequest($httpMethod, $url, array $options = [])
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $options[RequestOptions::HEADERS]['Authorization'] = 'Bearer ' . $this->authentication->getAccessToken();

        try {
            $response = $this->guzzleClient->request(
                $httpMethod,
                $url,
                $options
            );
        } catch (ClientException $e) {
            if (401 === $e->getResponse()->getStatusCode()) {
                $this->connect();
                try {
                    $options[RequestOptions::HEADERS]['Authorization'] = 'Bearer ' . $this->authentication->getAccessToken();
                    $response = $this->guzzleClient->request(
                        $httpMethod,
                        $url,
                        $options
                    );
                } catch (ClientException $e) {
                    throw new \Exception($e);
                }
            } else {
                throw $e;
            }
        }

        return $response;
    }

    /**
     * @throws \Exception
     */
    protected function connect()
    {
        $body = [
            'grant_type' => 'password',
            'username'   => $this->authentication->getUsername(),
            'password'   => $this->authentication->getPassword(),
        ];

        try {
            $response = $this->guzzleClient->post(Route::TOKEN, [
                RequestOptions::JSON    => $body,
                RequestOptions::AUTH    => [
                    $this->authentication->getClientId(),
                    $this->authentication->getSecret(),
                ],
                RequestOptions::HEADERS => [
                    //'Cookie'     => 'XDEBUG_SESSION=PHPSTORM',
                    'Content-Type' => 'application/json',
                ],
            ]);

            if (200 !== $response->getStatusCode()) {
                // TODO : create and handle exception (300.. etc)
                throw new \Exception($response->getBody()->getContents());
            }

            $responseContent = json_decode($response->getBody()->getContents(), true);

            $this->authentication->setAccessToken($responseContent['access_token']);
            $this->authentication->setRefreshToken($responseContent['refresh_token']);
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    protected function isConnected()
    {
        return null !== $this->authentication->getAccessToken();
    }
}
