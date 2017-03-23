<?php

require_once __DIR__.'/vendor/autoload.php';

$auth = new \Akeneo\Authentication(
    '3_2dcu2w95uxz4sw8kk8ksk488gkggggoo8ss8c8ogwgwowgo4cg',
    '9fqicgletjwgsgcw4cg0ccks0k0kokkgwcww80o8cww0kwco0',
    'admin',
    'admin'
);
$client = new Akeneo\Client(
    'http://pcd.dev/',
    $auth
);

$content = $client->getCategory('master');

var_dump($content);
