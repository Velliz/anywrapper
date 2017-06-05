<?php

//todo: only for testing purpose

include 'vendor/autoload.php';

#region permission checking
$client = new \wrapper\floors\Client(array(
    'server' => 'http://localhost/floors/api/',
    'identifier' => 'tanampohon'
), 'http://localhost/anywrapper');

$client->StartSession(false);
$permission = $client->IsHasPermission('SADMIN');

var_dump($permission);
#end region permission checking