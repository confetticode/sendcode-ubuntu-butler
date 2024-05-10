# SendCode Ubuntu Butler (on development)

## Requirements

- PHP: ^8.0

## Usage

Imagine your have a server, it's public IP address is 192.168.33.11 and allows to SSH via port 22. Your current device (desktop, laptop or another server) that can connect to it as "vagrant" system user within "/home/vagrant/.ssh/id_ed25519" private key file.

- Run any commands, Eg: lit directories and files inside /root/ directory:

```php
<?php

use SendCode\Ubuntu\Connection;
use SendCode\Ubuntu\Contracts\ExceptionInterface;

require __DIR__ . '/vendor/autoload.php';

$connection = (new Connection())
    ->setUser('vagrant')
    ->setHost('127.0.0.1')
    ->setPort(22)
    ->setKeyFile('/home/vagrant/.ssh/id_ed25519')
//    ->setLogFile(__DIR__ . '/log.txt')
;

try {
    $process = $connection->run("
echo 'List items inside /root/ directory:'

sudo ls -la /root
");
} catch (ExceptionInterface $exception) {
    //
}
```
