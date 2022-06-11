Dadapas Log
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

use Dadapas\Log\Log as Logger;

class Foo
{
    private $logger;

    public function __construct(Logger $logger = null)
    {
        $this->logger = $logger;
        $this->setPath( dirname(__DIR__)."/logs" );
    }

    public function throwException()
    {
        throw new Exception("An exception has thrown.");
    }

    public function doSomething()
    {
        if ($this->logger) {
            $this->logger->info('Doing work');
        }
           
        try {
            $this->throwException();
        } catch (Exception $e) {

            $this->logger->error($e->getMessage(), $e->getTrace());
        }

        // do something useful
    }
}
```

You can then pick one of the implementations of the interface to get a logger.

If you want to implement the interface, you can require this package and
implement `Psr\Log\LoggerInterface` in your code. Please read the
[specification text](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md)
for details.