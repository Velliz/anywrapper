# anywrapper

Class Wrapper and library for used with OAAS Anywhere

[![Latest Stable Version](https://poser.pugx.org/anywhere/wrapper/v/stable)](https://packagist.org/packages/puko/framework)
[![Total Downloads](https://poser.pugx.org/anywhere/wrapper/downloads)](https://packagist.org/packages/puko/framework)

## PDF requests sample
```
require 'AnywhereWrapper.php';
$pdf = new AnywherePdf(AnywhereWrapper::POST);
$pdf->setValue('Name', 'Someone');
$pdf->setValue('Age', '22');
$pdf->Send(API_URL);
```

## Email requests sample
```
require 'AnywhereWrapper.php';
$mail = new AnywhereMail(AnywhereWrapper::POST);
$mail->setTo('example@gmail.com');
$mail->setCc('example@outlook.com');
$mail->setBcc('example@yahoo.co.id');
$mail->setSubject('Anywhere Wrapper');
$mail->setValue('Name', 'Anywhere');
$mail->setValue('Age', '22');
$mail->setAttachment('qrcode.png', 'http://oaas-divelliz.rhcloud.com/qr/render?data=admin@example.co.id');
$mail->setAttachment('qrcode1.png', 'http://oaas-divelliz.rhcloud.com/qr/render?data=developer@example.co.id');
$mail->Send(API_URL);
```

## Images request sample
* TODO