<?php

namespace Akeneo\Client;

use Psr\Http\Message\RequestInterface;

/**
 * Interface HttpClientInterface
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface HttpClientInterface
{
    /**
     * @param RequestInterface $request
     * @param array            $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendRequest(RequestInterface $request, array $options = []);

    /**
     * @param string                               $method
     * @param string|UriInterface                  $uri
     * @param array                                $uriParameters
     * @param array                                $headers
     * @param string|null|resource|StreamInterface $body
     *
     * @return RequestInterface
     */
    public function createRequest($method, $uri, array $uriParameters = [], array $headers = [], $body = null);
}
