dadapas log
=======

[![Latest Stable Version](http://poser.pugx.org/dadapas/log/v)](https://packagist.org/packages/dadapas/log) [![Total Downloads](http://poser.pugx.org/dadapas/log/downloads)](https://packagist.org/packages/dadapas/log) [![Latest Unstable Version](http://poser.pugx.org/dadapas/log/v/unstable)](https://packagist.org/packages/dadapas/log) [![License](http://poser.pugx.org/dadapas/log/license)](https://packagist.org/packages/dadapas/log) [![PHP Version Require](http://poser.pugx.org/dadapas/log/require/php)](https://packagist.org/packages/dadapas/log)

This repository implements interfaces related to
[PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md).

Installation
------------

```bash
composer require dadapas/log
```

Usage
-----

If you need a logger, you can use the interface like this:

```php
<?php

use Dadapas\Log\{FileSystemAdapter, Log as Logger};

$localAdapter = new \League\Flysystem\Local\LocalFilesystemAdapter(
    // Determine log directory
    __DIR__.'/path/to/logs'
);

// The FilesystemOperator
$filesystem = new \League\Flysystem\Filesystem($localAdapter);
$filesysAdapter = new FileSystemAdapter($filesystem);

$logger = new Logger();
$logger->setAdapter($filesysAdapter);
```
To send a logger to an email will be like:

```php
// ...
use Dadapas\Log\PHPMailerAdapter;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

// $mail->SMTPDebug  = SMTP::DEBUG_SERVER;
// $mail->isSMTP();
// $mail->Host       = 'smtp.example.com';
// $mail->SMTPAuth   = true;

// ...
$adapter = new PHPMailerAdapter($mail);

// ...
$logger->setAdapter($adapter);
```

Make a log to the file
```php
// ...
try {
    throw new Exception("An exception has been thrown.");
} catch (Exception $e) {

    // Log to the the error message to file
    $logger->error($e->getMessage(), $e->getTrace());

} catch (\PHPMailer\PHPMailer\Exception $e) {

    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
```

You can then pick one of the implementations of the interface to get a logger.

If you want to implement the interface, you can require this package and
implement `Psr\Log\LoggerInterface` in your code. Please read the
[specification text](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md)
for details.
