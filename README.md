# anywrapper [beta]

PHP based wrapper for **Anywhere** output as-a service. visit [Anywhere](https://anywhere.cf) and register for account.

[![Latest Stable Version](https://poser.pugx.org/anywhere/wrapper/v/stable)](https://packagist.org/packages/anywhere/wrapper)
[![Total Downloads](https://poser.pugx.org/anywhere/wrapper/downloads)](https://packagist.org/packages/anywhere/wrapper)

### Installation
add it to your composer.json
```
"require": {
    "anywhere/wrapper": "dev-master"
}
```

### PDF requests sample
always choose POST for your request URL.
```
$pdf = new AnywherePdf(Wrapper::POST);
$pdf->setValue('Name', 'Someone');
$pdf->setValue('Age', '22');
$pdf->Send(API_URL);
```

### Email requests sample
always choose POST for your request URL.
```
require 'AnywhereWrapper.php';
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
* TODO

### Extras
made with <3 from bandung, indonesia.
