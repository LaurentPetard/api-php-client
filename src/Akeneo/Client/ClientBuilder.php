<?php

namespace Akeneo\Client;

use Akeneo\Authentication;
use Akeneo\Guzzle\HttpClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

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

    /**
     * @param string         $baseUri
     * @param Authentication $authentication
     *
     * @return ClientInterface
     */
    public function build($baseUri, Authentication $authentication)
    {
        //$container = new ContainerBuilder();
        //$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/Resources/config'));
        //$loader->load('config.yml');

        $guzzleClient = new GuzzleClient([
            'base_uri' => $baseUri,
            RequestOptions::TIMEOUT  => static::TIMEOUT,
        ]);

        $httpClient = new HttpClient($guzzleClient);

        $resourceClient = new ResourceClient($httpClient, $authentication);

        return new AkeneoPimClient($resourceClient);
    }
}
