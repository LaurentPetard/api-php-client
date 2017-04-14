<?php

namespace Akeneo;

/**
 * Class UrlGenerator
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UrlGenerator
{
    protected $baseUri;

    /**
     * @param $baseUri
     */
    public function __construct($baseUri)
    {
        $this->baseUri = rtrim($baseUri, '/');
    }

    /**
     * @param string $route
     * @param array  $uriParameters
     * @param array  $queryParameters
     *
     * @return string
     */
    public function generate($route, array $uriParameters = [], array $queryParameters = [])
    {
        $uriParameters = $this->encodeUriParameters($uriParameters);

        $uri = $this->baseUri.'/'.vsprintf(ltrim($route, '/'), $uriParameters);

        if (! empty($queryParameters)) {
            $queryParameters = $this->encodeQueryParameters($queryParameters);
            $uri .= '?'.http_build_query($queryParameters, null, '&', PHP_QUERY_RFC3986);
        }

        return $uri;
    }

    protected function encodeUriParameters(array $uriParameters) {
        // FIXME: better handling of slashes ?
        return array_map(function($uriParameter) {
            $uriParameter = rawurlencode($uriParameter);

            return preg_replace('~\%2F~', '/', $uriParameter);
        }, $uriParameters);
    }

    protected function encodeQueryParameters(array $queryParameters) {
        array_walk_recursive($queryParameters, function(&$parameter) {
            $parameter = rawurlencode($parameter);
        });

        return $queryParameters;
    }
}
