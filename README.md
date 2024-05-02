# SendCode Ubuntu Butler (on development)

## Usage

```php
<?php

use SendCode\Ubuntu\Server;

$server = (new Server)
    ->setIpAddress('192.168.33.11')
    ->setSshUser('root')
    ->setSshPort(22)
    ->setKeyFile('/path/to/id_rsa');
    
$schedule = (new Schedule)
    ->setId(1234)
    ->setTitle('Test schedule on Ubuntu servers.')
    ->setExpression('* * * * *')
    ->setSystemUser('test-user')
    ->setCommand('date >> /tmp/date.log');
    
$server
    ->setLogFile('/path/to/log.txt')
    ->addSchedule($schedule)
    ->updateSchedule($schedule)
    ->removeSchedule($schedule);
```

File "/etc/cron.d/sendcode-schedule-1234" is created with the following content.

```plain
# Test schedule on Ubuntu servers
* * * * * test-user date >> /tmp/date.log
```