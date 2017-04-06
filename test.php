<?php

require_once __DIR__.'/vendor/autoload.php';


$auth = new \Akeneo\Authentication(
    '1_2kchrfmnds84g8c4o88wc44css0goccswokg8g8o0ok0o04k0k',
    '2v7oi6o6km0wscsgkwws4wo0o84wk4wk4o0ccwosckgw40k80',
    'admin',
    'admin'
);

$clientBuilder = new \Akeneo\Client\ClientBuilder();
$client = $clientBuilder->build('http://pcd.dev/', $auth);

//$categories = [
//    [
//        'code' => 'tvs_projectors',
//        'parent' => null,
//        'labels' => [
//            'en_US' => 'TVs and projectors',
//            'fr_FR' => 'La TV en France !',
//        ],
//    ],
//    [
//        'code' => 'cameras',
//        'parent' => null,
//        'labels' => [
//            'en_US' => 'Cameras',
//            'fr_FR' => 'French Cameras !',
//        ],
//    ],
//];
//
//
//$client->partialUpdateCategories($categories);
//
//return;
//
//// Media file
//
//
//$response =$client->getMediaFiles(['limit' => 3]);
//
//foreach ($response as $mediaFile) {
//    var_dump($mediaFile);
//}
//return;
//
//$client->createMediaFile('/home/laurent/Images/akeneo_logo.png', [
//    'identifier' => '10699783',
//    'attribute' => 'secondary_image',
//    'scope' => null,
//    'locale' => null,
//]);
//
//return;
//
////$client->downloadMediaFile('d/7/5/d/d75d75ba585ceea820d3505f7e707b9c7ad8e4f3_10806799_1356.jpg', '/var/tmp/testDownloadResource.jpg') ;return;
//
//// Attribute
//
//var_dump($client->getAttribute('auto_exposure'));
//return;
///*
//
//$attributes = $client->getAttributes(['limit' => 2]);
//
//foreach ($attributes as $attribute) {
//    var_dump($attribute);
//}
//return;
//
//$attribute = $client->createAttribute(
//    [
//        'code'                   => 'a_new_text',
//        'type'                   => 'pim_catalog_text',
//        'group'                  => 'technical',
//        'unique'                 => false,
//        'useable_as_grid_filter' => false,
//        'allowed_extensions'     => [],
//        'metric_family'          => null,
//        'default_metric_unit'    => null,
//        'reference_data_name'    => null,
//        'available_locales'      => [],
//        'max_characters'         => null,
//        'validation_rule'        => null,
//        'validation_regexp'      => null,
//        'wysiwyg_enabled'        => null,
//        'number_min'             => null,
//        'number_max'             => null,
//        'decimals_allowed'       => null,
//        'negative_allowed'       => null,
//        'date_min'               => null,
//        'date_max'               => null,
//        'max_file_size'          => null,
//        'minimum_input_length'   => null,
//        'sort_order'             => 12,
//        'localizable'            => false,
//        'scopable'               => false,
//        'labels'                 => [],
//    ]
//);
//
//*/
//
//$client->partialUpdateAttribute(
//    'a_new_text',
//    [
//        'group' => 'marketing',
//        'labels' => ['en_US' => 'test Create Attribute'],
//    ]
//);
//
//
//var_dump($client->getAttribute('a_new_text'));
//
//
//
//return;
//
//// Category
//
//var_dump($client->getCategory('master'));
//
//$client->createCategory([
//    'code'   => 'testCreate',
//    'parent' => 'master',
//    'labels' => [
//        'en_US' => 'Category C',
//        'fr_FR' => 'Catégorie C',
//    ],
//]);
//
//
//$client->partialUpdateCategory('testCreate', [
//    'labels' => [
//        'en_US' => 'Category US',
//        'fr_FR' => 'Catégorie C',
//    ],
//]);
//
//$content = $client->getCategory('testCreate');
//var_dump($content);


// Category

$auth = new \Akeneo\Authentication(
    '1_2kchrfmnds84g8c4o88wc44css0goccswokg8g8o0ok0o04k0k',
    '2v7oi6o6km0wscsgkwws4wo0o84wk4wk4o0ccwosckgw40k80',
    'admin',
    'admin'
);

$clientBuilder = new \Akeneo\Client\ClientBuilder();
$client = $clientBuilder->buildObjectClient('http://pcd.dev/', $auth);

$category = $client->getCategory('headphones');

$code = 'test1'.rand(0, 1000000);
$category->setCode($code);
$category->setParent('master');
$client->createCategory($category);

$category = $client->getCategory($code);
