# anywrapper [beta]

PHP based client for **Anywhere** and **Floors**.

[![Latest Stable Version](https://poser.pugx.org/anywhere/wrapper/v/stable)](https://packagist.org/packages/anywhere/wrapper)
[![Total Downloads](https://poser.pugx.org/anywhere/wrapper/downloads)](https://packagist.org/packages/anywhere/wrapper)

Add library to your composer.json
```
"require": {
    "anywhere/wrapper": "dev-master"
}
```

## Anywhere Usage

### PDF requests sample
always choose POST for your request URL.
```php
$pdf = new AnywherePdf(Wrapper::POST);

$pdf->setValue('Name', 'Someone');
$pdf->setValue('Age', '22');

$pdf->Send(API_URL);
```

### Email requests sample
always choose POST for your request URL.
```php
$mail = new AnywhereMail(Wrapper::POST);

$mail->setTo('example@gmail.com');

$mail->setCc('example@outlook.com');
$mail->setBcc('example@yahoo.co.id');

$mail->setSubject('Anywhere Wrapper');

$mail->setValue('Name', 'Anywhere');
$mail->setValue('Age', '22');

$mail->setAttachment('qrcode.png', 'https://anywhere.cf/qr/render?data=admin@example.co.id');
$mail->setAttachment('qrcode1.png', 'https://anywhere.cf/qr/render?data=developer@example.co.id');

$mail->Send(API_URL);
```

### Images request sample
> TODO

## Floors Usage

### Initialize
```php
use \wrapper\floors\Client as FloorsWrapper;

$config = array(
    'server' => 'http://localhost/floors/api/',
    'identifier' => 'anywrapper'
);
$redirect = 'http://localhost/anywrapper';

$client = new FloorsWrapper($config, $redirect);
$client->StartSession(false);
```

### Available Method
```php
$confirm = $client->ConfirmPassword('test', 'test');
$permission = $client->IsHasPermission('USER');
$permission_all = $client->GetPermission();
$profile = $client->GetLoginInformation();
$profile_pic = $client->GetProfilePictureURL();
$user = $client->GetUserData();
```

### Extras
made with <3 from bandung, indonesia.
