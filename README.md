# SendCode Ubuntu Butler (on development)

## Requirements

- PHP: ^8.0

## Usage

Imagine your have a server, it's public IP address is 192.168.33.11 and allows to SSH via port 22. Your current device (desktop, laptop or another server) that can connect to it as "vagrant" system user within "/home/vagrant/.ssh/id_ed25519" private key file.

- Run any commands, Eg: lit directories and files inside /root/ directory:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use SendCode\Ubuntu\Contracts\ExceptionInterface;
use SendCode\Ubuntu\Server;

$server = (new Server)
    ->setIpAddress('192.168.33.11')
    ->setSshPort(22)
    ->setKeyFile('/home/vagrant/.ssh/id_ed25519');

try {
    $process = $server->sshAs('vagrant')->run("
echo 'List directories and files inside /root/ directory:'

sudo su

ls -la /root/
");
} catch (ExceptionInterface $exception) {
    //
}
```

- Run a specific script, Eg: add a schedule to the server.

```
<?php

require __DIR__ . '/vendor/autoload.php';

use SendCode\Ubuntu\Contracts\ExceptionInterface;
use SendCode\Ubuntu\Resources\Schedule;
use SendCode\Ubuntu\Scripts\AddScheduleScript;
use SendCode\Ubuntu\Server;

$schedule = (new Schedule)
    ->setId(123)
    ->setName('Test schedule')
    ->setCronExpression('* * * * *')
    ->setSystemUser('vagrant')
    ->setCommand('date >> /tmp/date.log');

$server = (new Server)
    ->setIpAddress('192.168.33.11')
    ->setSshPort(22)
    ->setKeyFile('/home/vagrant/.ssh/id_ed25519');

$connection = $server->sshAs('vagrant');

$script = new AddScheduleScript($connection, $schedule);

try {
    $script->run();
} catch (ExceptionInterface $exception) {
    //
}
```