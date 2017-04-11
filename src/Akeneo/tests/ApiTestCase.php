<?php

namespace Akeneo\tests;

use Akeneo\Authentication;
use GuzzleHttp\Psr7\Response;

abstract class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $mock
     *
     * @return ClientMocker
     */
    protected function createAuthenticatedClient(array $mock)
    {
        $authentication = new Authentication(
            'client_id',
            'secret',
            'user',
            'password'
        );


        $body = <<<JSON
{
  "access_token": "foo",
  "expires_in": 3600,
  "token_type": "bearer",
  "scope": null,
  "refresh_token": "bar"
}
JSON;
        array_unshift($mock, new Response(200, [], $body));
        $clientMocker = new ClientMocker('http://api.akeneo.com/', $authentication, $mock);

        return $clientMocker;
    }

    protected function assert()
    {

    }
}
