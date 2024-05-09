<?php

namespace Tests\SendCode\Ubuntu;

use PHPUnit\Framework\TestCase;
use SendCode\Ubuntu\Server;

class ServerTest extends TestCase
{
    public function test_it_throws_exception_if_ip_address_invalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $server = new Server();
        $server->setIpAddress('invalid-ip');
    }

    public function test_it_throws_exception_if_ssh_port_invalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $server = new Server();
        $server->setSshPort(65536);
    }

    public function test_it_throws_exception_if_key_file_invalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $server = new Server();
        $server->setKeyFile('/path/non-exist-file');
    }
}
