<?php

namespace Tests\SendCode\Ubuntu;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SendCode\Ubuntu\Connection;

class ConnectionTest extends TestCase
{
    public function test_it_throws_exception_if_ip_address_invalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $connection = new Connection();
        $connection->setIpAddress('invalid-ip');
    }

    public function test_it_throws_exception_if_port_invalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $connection = new Connection();
        $connection->setPort(65536);
    }

    public function test_it_throws_exception_if_key_file_invalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $connection = new Connection();
        $connection->setKeyFile('/path/non-existing-file');
    }
}
