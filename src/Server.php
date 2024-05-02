<?php

namespace SendCode\Ubuntu;

use SendCode\Ubuntu\Contracts\ServerInterface;

class Server implements ServerInterface
{
    private string $ipAddress;
    private int $sshPort;
    private string $keyFile;

    public function __construct(string $ipAddress, int $sshPort, string $keyFile)
    {
        $this->ipAddress = $ipAddress;
        $this->sshPort = $sshPort;
        $this->keyFile = $keyFile;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function getSshPort(): int
    {
        return $this->sshPort;
    }

    public function getKeyFile(): string
    {
        return $this->keyFile;
    }
}