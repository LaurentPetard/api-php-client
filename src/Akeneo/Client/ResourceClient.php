<?php

namespace Akeneo\Client;

use Akeneo\Authentication;
use Akeneo\HttpMethod;
use Akeneo\Pagination\Page;
use Akeneo\Pagination\Paginator;
use Akeneo\RequestOption;
use Akeneo\Route;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\RequestInterface;
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
    /* @var Authentication */
    protected $authentication;

    /** @var GuzzleClient */
    protected $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     * @param Authentication      $authentication
     */
    public function __construct(HttpClientInterface $httpClient, Authentication $authentication)
    {
        $this->authentication = $authentication;
        $this->httpClient = $httpClient;
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
        $request = $this->httpClient->createRequest(HttpMethod::GET, $url);
        $response = $this->performAuthenticatedRequest($request);

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
        $headers = ['Content-Type' => 'application/json'];
        $body = json_encode($data);

        $request = $this->httpClient->createRequest(HttpMethod::POST, $url, [], $headers, $body);
        $response = $this->performAuthenticatedRequest($request);

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
        $headers = ['Content-Type' => 'application/json'];
        $body = json_encode($data);

        $request = $this->httpClient->createRequest(HttpMethod::PATCH, $url, [], $headers, $body);
        $response = $this->performAuthenticatedRequest($request);

        if (204 !== $response->getStatusCode() && 201 !== $response->getStatusCode()) {
            throw new Exception($response->getStatusCode() . '--' . $response->getBody()->getContents());
        }
    }

    public function partialUpdateResources($url, array $resourcesData)
    {
        $body = '';
        foreach ($resourcesData as $resourceData) {
            $body .= json_encode($resourceData) . PHP_EOL;
        }

        $headers = ['Content-Type' => 'application/vnd.akeneo.collection+json'];

        $request = $this->httpClient->createRequest(HttpMethod::PATCH, $url, [], $headers, $body);
        $response = $this->performAuthenticatedRequest($request);

        if (200 !== $response->getStatusCode()) {
            throw new Exception($response->getStatusCode() . '--' . $response->getBody()->getContents());
        }
    }

    /**
     * @param       $uri
     * @param array $uriParameters
     *
     * @throws Exception
     *
     * @return Paginator
     */
    public function getListResources($uri, array $uriParameters = [])
    {
        $request = $this->httpClient->createRequest(HttpMethod::GET, $uri, $uriParameters);
        $response =  $this->performAuthenticatedRequest($request);

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
     * @param string                          $url
     * @param string|resource|StreamInterface $target
     *
     * @throws \Exception
     */
    public function downloadResource($url, $target)
    {
        $request = $this->httpClient->createRequest(HttpMethod::GET, $url);
        $options = [RequestOption::SAVE_TO => $target];

        $response = $this->performAuthenticatedRequest($request, $options);

        if (200 !== $response->getStatusCode()) {
            throw new Exception();
        }
    }

    /**
     * @param RequestInterface $request
     * @param array            $options
     *
     * @throws \Exception
     *
     * @return ResponseInterface
     */
    public function performAuthenticatedRequest(RequestInterface $request, array $options = [])
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $request = $request->withHeader('Authorization', 'Bearer ' . $this->authentication->getAccessToken());

        // TODO: use specifics exceptions
        try {
            $response = $this->httpClient->sendRequest($request, $options);
        } catch (ClientException $e) {
            if (401 === $e->getResponse()->getStatusCode()) {
                $this->connect();
                try {
                    $request = $request->withHeader('Authorization', 'Bearer ' . $this->authentication->getAccessToken());
                    $response = $this->httpClient->sendRequest($request, $options);
                } catch (ClientException $e) {
                    throw new \Exception($e);
                }
            } else {
                throw $e;
            }
        }

        return $response;
    }

    public function performMultipartRequest($url, array $requestData)
    {
        $options = [RequestOption::MULTIPART => $requestData];
        $headers = ['Accept' => '*/*'];

        $request = $this->httpClient->createRequest(HttpMethod::POST, $url, [], $headers);
        $response = $this->performAuthenticatedRequest($request, $options);

        var_dump($response->getStatusCode());
    }

    /**
     * @throws \Exception
     */
    protected function connect()
    {
        $body = json_encode([
            'grant_type' => 'password',
            'username'   => $this->authentication->getUsername(),
            'password'   => $this->authentication->getPassword(),
        ]);

        $headers = [
            'Authorization' => 'Basic '.base64_encode($this->authentication->getClientId().':'.$this->authentication->getSecret()),
            'Content-Type' => 'application/json',
        ];

        try {
            $request = $this->httpClient->createRequest(HttpMethod::POST, Route::TOKEN, [], $headers, $body);
            $response = $this->httpClient->sendRequest($request);


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
