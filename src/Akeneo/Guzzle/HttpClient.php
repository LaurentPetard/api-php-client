<?php

namespace Akeneo\Guzzle;

use Akeneo\Client\HttpClientInterface;
use Akeneo\RequestOption;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class HttpClient
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class HttpClient implements HttpClientInterface
{
    protected $guzzleClient;

    /**
     * @param GuzzleClient $guzzleClient
     */
    public function __construct(GuzzleClient $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @inheritdoc
     */
    public function sendRequest(RequestInterface $request, array $options = [])
    {
        if (isset($options[RequestOption::SAVE_TO]) && RequestOption::SAVE_TO !== GuzzleRequestOptions::SINK) {
            $options[GuzzleRequestOptions::SINK] = $options[RequestOption::SAVE_TO];
            unset($options[RequestOption::SAVE_TO]);
        }

        if (isset($options[RequestOption::MULTIPART]) && RequestOption::MULTIPART !==  GuzzleRequestOptions::MULTIPART) {
            $options[GuzzleRequestOptions::MULTIPART] = $options[RequestOption::MULTIPART];
            unset($options[RequestOption::MULTIPART]);
        }

        return $this->guzzleClient->send($request, $options);
    }

    /**
     * @inheritdoc
     */
    public function createRequest($method, $uri, array $uriParameters = [], array $headers = [], $body = null)
    {
        $uri = $this->buildUri($uri, $uriParameters);

        return new Request($method, $uri, $headers, $body);
    }

    protected function buildUri($uri, array $uriParameters = [])
    {
        if (! $uri instanceof UriInterface) {
            $uri = new Uri($uri);
        }

        if (! empty($uriParameters)) {
            $uriQuery = http_build_query($uriParameters, null, '&', PHP_QUERY_RFC3986);
            $uri = $uri->withQuery($uriQuery);
        }

        return $uri;
    }
}
