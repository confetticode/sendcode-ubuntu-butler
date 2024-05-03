<?php

require __DIR__ . '/vendor/autoload.php';

use SendCode\Ubuntu\Resources\Schedule;
use SendCode\Ubuntu\Scripts\AddScheduleScript;
use SendCode\Ubuntu\Server;

$server = (new Server)
    ->setIpAddress('192.168.33.11')
    ->setSshPort(22)
    ->setKeyFile('/path/to/id_rsa');

// List directories and files inside "/" on the server.
$result = $server->sshAs('root')->run('ls -la /');

if ($result->isSuccessful()) {
    echo 'It can list.';
} else {
    echo 'It can not list.';
}

// Add a new cronjob to the server.
$schedule = (new Schedule)
    ->setId(123)
    ->setName('Test schedule on Ubuntu servers')
    ->setCronExpression('* * * * *')
    ->setSystemUser('test-user')
    ->setCommand('date >> /tmp/date.log');

$result = $server
    ->sshAs('root-alternative')
    ->useLogfile('/path/to/log.txt')
    ->runScript(
        new AddScheduleScript($schedule)
    );

if ($result->isSuccessful()) {
    echo 'The schedule is added to the server successfully.';
} else {
    echo 'Failed to add the schedule to the server due some reasons.';
}
