<?php

use \wrapper\floors\Client as FloorsWrapper;

//todo: only for testing purpose

include 'vendor/autoload.php';

#region permission checking
$config = array(
    'server' => 'http://localhost/floors/api/',
    'identifier' => 'anywrapper'
);
$redirect = 'http://localhost/anywrapper';

$client = new FloorsWrapper($config, $redirect);
$client->StartSession(false);

$confirm = $client->ConfirmPassword('test', 'test');
var_dump($confirm);
echo '<br>';
$permission = $client->IsHasPermission('DFUSER');
var_dump($permission);
echo '<br>';
$permission_all = $client->GetPermission();
var_dump($permission_all);
echo '<br>';
$profile = $client->GetLoginInformation();
var_dump($profile);
echo '<br>';
$profile_pic = $client->GetProfilePictureURL();
var_dump($profile_pic);
echo '<br>';
$user = $client->GetUserData();
var_dump($user);
echo '<br>';



#end region permission checking