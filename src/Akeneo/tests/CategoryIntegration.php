<?php

namespace Akeneo\tests;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7;

class CategoryIntegration extends ApiTestCase
{
    public function testPartialUpdateCategories()
    {
        $responseBody = <<< JSON
{"line":1,"code":"tvs_projectors","status_code":204}
{"line":2,"code":"cameras","status_code":201}
JSON;

        $clientMocker = $this->createAuthenticatedClient([
            new Response(200, [], $responseBody)
        ]);

        $categories = [
            [
                'code' => 'tvs_projectors',
                'parent' => null,
                'labels' => [
                    'en_US' => 'TVs and projectors',
                    'fr_FR' => 'La TV en France !',
                ],
            ],
            [
                'code' => 'cameras',
                'parent' => null,
                'labels' => [
                    'en_US' => 'Cameras',
                    'fr_FR' => 'French Cameras !',
                ],
            ],
        ];

        $clientMocker->getClient()->partialUpdateCategories($categories);

        $expectedRequest = <<<JSON
{"code":"tvs_projectors","parent":null,"labels":{"en_US":"TVs and projectors","fr_FR":"La TV en France !"}}
{"code":"cameras","parent":null,"labels":{"en_US":"Cameras","fr_FR":"French Cameras !"}}
JSON;


        $history = $clientMocker->getHistory()[1];
        $this->assertSame($expectedRequest, $history['doAuthenticatedRequest']->getBody()->getContents());
        $this->assertSame(200, $history['response']->getStatusCode());
        $this->assertSame($responseBody, $history['response']->getBody()->getContents());
    }

    public function testPartialUpdateCategory()
    {
        $clientMocker = $this->createAuthenticatedClient([
            new Response(204, ['Location' => 'http://api.akeneo.com/api/rest/v1/categories/tvs_projectors'])
        ]);

        $category = [
            'code' => 'tvs_projectors',
            'parent' => null,
            'labels' => [
                'en_US' => 'TVs and projectors',
                'fr_FR' => 'La TV en France !',
            ],
        ];

        $clientMocker->getClient()->partialUpdateCategory('tvs_projectors', $category);

        $expectedRequest = json_encode($category);

        $history = $clientMocker->getHistory()[1];
        $this->assertSame($expectedRequest, $history['doAuthenticatedRequest']->getBody()->getContents());
        $this->assertSame(204, $history['response']->getStatusCode());
        $this->assertSame('', $history['response']->getBody()->getContents());
    }

    public function testGetCategory()
    {
        $responseBody = <<<JSON
{
    "code": "tvs_projectors",
	"parent": null,
	"labels": {
        "en_US": "TVs and projectors",
		"fr_FR": "La TV en France !"
	}
}
JSON;

        $expectedResponse = json_decode($responseBody, true);

        $clientMocker = $this->createAuthenticatedClient([
            new Response(200, ['Location' => 'http://api.akeneo.com/api/rest/v1/categories/tvs_projectors'], $responseBody)
        ]);


        $response = $clientMocker->getClient()->getCategory('tvs_projectors');

        $history = $clientMocker->getHistory()[1];
        $this->assertSame('GET', $history['doAuthenticatedRequest']->getMethod());
        $this->assertSame('/api/rest/v1/categories/tvs_projectors', $history['doAuthenticatedRequest']->getRequestTarget());
        $this->assertSame(200, $history['response']->getStatusCode());
        $this->assertSame($expectedResponse, $response);
    }

}
