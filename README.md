# anywrapper

PHP based client for **Anywhere**

[![Latest Stable Version](https://poser.pugx.org/anywhere/wrapper/v/stable)](https://packagist.org/packages/anywhere/wrapper)
[![Total Downloads](https://poser.pugx.org/anywhere/wrapper/downloads)](https://packagist.org/packages/anywhere/wrapper)

Add this library by executing composer command: `composer require anywhere/wrapper` or add this library to your composer.json

```
"require": {
    "anywhere/wrapper": "0.2.0"
}
```

> since version 0.2.0 floors support was dropped to maintain library size and relevance

## Anywrapper Usage

**PDF requests sample**

Always choose **POST** for your request URL. Because GET works without use of this library.

```php
$pdf = new wrapper\anywhere\Pdf(Wrapper::POST);

$pdf->setValue('Name', 'Someone');
$pdf->setValue('Age', '22');

$pdf->Send(API_URL, false);
```

**Email requests sample**

Always choose POST for your request URL.

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

$mail->Send(API_URL, false);
```

**Images request sample**

```php
$images = new \wrapper\anywhere\Image();
$images->setImageContentUrl('http://anywhere.test/qr/render?data=05b810384abc26a2365e1108501534a2&size=300&label=Anywhere');

$images->Send("http://anywhere.test/images/render/4f1bf2f1a5546d2d567043d14f1a14ae/1");
```

---

_Extras_

made with <3 from bandung, indonesia.
