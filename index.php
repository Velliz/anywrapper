<?php

use \wrapper\floors\Client as FloorsWrapper;

include 'vendor/autoload.php';

#region permission checking
$config = array(
    'server' => 'http://localhost/floors/api/',
    'identifier' => 'anywrapper'
);
$redirect = 'http://localhost/anywrapper';

$client = new FloorsWrapper($config, $redirect);
$client->StartSession(false);
