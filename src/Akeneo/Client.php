<?php

namespace Akeneo;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Client of Akeneo PIM
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Client
{
    const TIMEOUT = 2.0;

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
        //$container = new ContainerBuilder();
        //$loader = new YamlFileLoader($container, new FileLocator(__DIR__));
        //$loader->load('client.yml');

        $this->authentication = $authentication;

        $this->guzzleClient = new GuzzleClient([
            'base_uri' => $baseUri,
            RequestOptions::TIMEOUT  => static::TIMEOUT,
        ]);
    }

    /**
     * @param Authentication $authentication
     *
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

            $responseContent = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

            $this->authentication->setAccessToken($responseContent['access_token']);
            $this->authentication->setRefreshToken($responseContent['refresh_token']);
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param string $code
     *
     * @return array
     */
    public function getCategory($code)
    {
        $url = sprintf(Route::GET_CATEGORY, urlencode($code));

        return $this->get($url);
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getCategories(array $options = [])
    {
        return $this->get(Route::GET_CATEGORIES, $options);
    }


    /**
     * @param string $url
     * @param array  $options
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function get($url, array $options = [])
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
            'query_parameters' => [],
            'headers'          => [],
        ]);

        try {
            $options = $resolver->resolve($options);
        } catch (\InvalidArgumentException $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }

        if (!$this->isConnected()) {
            $this->connect();
        }

        $headers = array_merge(
            $options['headers'],
            ['Authorization' => 'Bearer ' . $this->authentication->getAccessToken()]
        );

        try {
            $response = $this->guzzleClient->get($url, [
                RequestOptions::QUERY   => $options['query_parameters'],
                RequestOptions::HEADERS => $headers,
            ]);
        } catch (ClientException $e) {
            if (401 === $e->getResponse()->getStatusCode()) {
                $this->connect();
            }
            try {
                $response = $this->guzzleClient->get($url, [
                    RequestOptions::QUERY   => $options['query_parameters'],
                    RequestOptions::HEADERS => $headers,
                ]);
            } catch (ClientException $e) {
                throw new \Exception($e);
            }
        }

        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }

    protected function isConnected()
    {
        return null !== $this->authentication->getAccessToken();
    }
}
