<?php

//todo: only for testing purpose

include 'vendor/autoload.php';

$guzzle = new \GuzzleHttp\Client([
    'base_uri' => 'http://localhost/floors/api/',
    'timeout' => 2.0,
]);
$response = $guzzle->request('POST', 'authorized', [
    'form_params' => [
        'token' => 'tLrLmeryfC',
        'sso' => 'b57b22e6deed7ce29d6e08e096ea3180ad13d005'
    ]
]);

$body = $response->getBody();
$stringBody = (string) $body;
var_dump($stringBody);
