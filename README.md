Dadapas Log
=======

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

use Dadapas\Log\Log;

class Foo
{
    private $logger;

    public function __construct(Log $logger = null)
    {
        $this->logger = $logger;
        $this->setPath( dirname(__DIR__)."/logs" );
    }

    public function doSomething()
    {
        if ($this->logger) {
            $this->logger->info('Doing work');
        }
           
        try {
            $this->doSomethingElse();
        } catch (Exception $exception) {

            $this->logger->error('An exception has thrown.', $e->getTrace());
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