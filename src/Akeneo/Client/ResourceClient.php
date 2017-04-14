<?php

namespace Akeneo\Client;

use Akeneo\Authentication;
use Akeneo\HttpMethod;
use Akeneo\Route;
use Akeneo\UrlGenerator;
use Http\Client\HttpClient;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;
use Psr\Http\Message\ResponseInterface;

/**
 * Client of Akeneo PIM
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class ResourceClient
{
    const TIMEOUT = -1;

    /* @var Authentication */
    protected $authentication;

    /** @var HttpClient */
    protected $httpClient;

    /** @var RequestFactory */
    protected $requestFactory;

    /** @var StreamFactory */
    protected $streamFactory;

    protected $urlGenerator;

    /**
     * @param Authentication $authentication
     * @param HttpClient     $httpClient
     * @param RequestFactory $requestFactory
     */
    public function __construct(Authentication $authentication, UrlGenerator $urlGenerator, HttpClient $httpClient, RequestFactory $requestFactory, StreamFactory $streamFactory)
    {
        $this->authentication = $authentication;
        $this->urlGenerator = $urlGenerator;
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @param string $url
     * @param array  $headers
     *
     * @throws Exception
     *
     * @return array
     */
    public function getResource($url, array $headers = [])
    {
        $response = $this->sendAuthenticatedRequest(HttpMethod::GET, $url, $headers);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception($response->getBody()->getContents());
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $url
     * @param array  $data
     *
     * @throws Exception
     */
    public function createResource($url, array $data, array $headers = [])
    {
        $headers = array_merge($headers, ['Content-Type' => 'application/json']);
        $body = json_encode($data);

        $response = $this->sendAuthenticatedRequest(HttpMethod::POST, $url, $headers, $body);

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
    public function partialUpdateResource($url, array $data, array $headers = [])
    {
        $headers = array_merge($headers, ['Content-Type' => 'application/json']);
        $body = json_encode($data);

        $response = $this->sendAuthenticatedRequest(HttpMethod::PATCH, $url, $headers, $body);

        if (in_array($response->getStatusCode(), [200, 201])) {
            throw new Exception($response->getStatusCode() . '--' . $response->getBody()->getContents());
        }
    }

    /**
     * @param string             $url
     * @param array|\Traversable $resources
     * @param array              $headers
     *
     * @throws \Exception
     */
    public function partialUpdateResources($url, $resources, array $headers = [])
    {
        if (!is_array($resources) && !$resources instanceof \Traversable) {
            throw new \InvalidArgumentException('The parameter resourcesData must be an array or implements Traversable');
        }

        $headers = array_merge($headers, ['Content-Type' => 'application/vnd.akeneo.collection+json']);

        $streamBuild = function() use($resources) {
            $isFirstLine = true;
            foreach ($resources as $resourceData) {
                yield ($isFirstLine ? '' : PHP_EOL).json_encode($resourceData);
                $isFirstLine = false;
            }
        };

        $streamedBody = $this->streamFactory->createStream($streamBuild());

        $response = $this->sendAuthenticatedRequest(HttpMethod::PATCH, $url, $headers, $streamedBody);

        if (!in_array($response->getStatusCode(), [200, 201])) {
            throw new \Exception($response->getStatusCode() . '--' . $response->getBody()->getContents());
        }
    }

    /**
     * @param string $url
     * @param string $filePath
     *
     * @throws \Exception
     */
    public function downloadResource($url, $filePath)
    {
        // TODO
    }

    /**
     * @param string                               $httpMethod
     * @param string|UriInterface                  $uri
     * @param array                                $headers
     * @param resource|string|StreamInterface|null $body
     *
     * @throws \Exception
     *
     * @return ResponseInterface
     */
    public function sendAuthenticatedRequest($httpMethod, $uri, array $headers = [], $body = null)
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $headers = array_merge($headers, ['Authorization' => 'Bearer ' . $this->authentication->getAccessToken()]);
        $request = $this->requestFactory->createRequest($httpMethod, $uri, $headers, $body);

        $response = $this->httpClient->sendRequest($request);

        // FIXME Refactor connection fail-over
        if (401 === $response->getStatusCode()) {
            $this->connect();

            $headers = array_merge($headers, ['Authorization' => 'Bearer ' . $this->authentication->getAccessToken()]);
            $request = $this->requestFactory->createRequest($httpMethod, $uri, $headers, $body);

            return $this->httpClient->sendRequest($request);
        }

        return $response;
    }

    public function sendMultipartRequest($url, array $requestData)
    {
        $streamBuilder = new MultipartStreamBuilder($this->streamFactory);

        foreach ($requestData as $requestDatum) {
            $options = isset($requestDatum['options']) ? $requestDatum['options'] : [];
            $streamBuilder->addResource($requestDatum['name'], $requestDatum['contents'], $options);
        }

        $multipartStream = $streamBuilder->build();
        $boundary = $streamBuilder->getBoundary();
        $headers = [
            'Content-Type' => sprintf('multipart/form-data; boundary="%s"', $boundary),
            'Accept'       => '*/*',
        ];

        $this->sendAuthenticatedRequest(HttpMethod::POST, $url, $headers, $multipartStream);
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

        $url = $this->urlGenerator->generate(Route::TOKEN);
        $request = $this->requestFactory->createRequest(HttpMethod::POST, $url, $headers, $body);

        try {
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

    /**
     * @return bool
     */
    protected function isConnected()
    {
        return null !== $this->authentication->getAccessToken();
    }
}
