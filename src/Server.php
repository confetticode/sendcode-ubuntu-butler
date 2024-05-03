<?php

namespace SendCode\Ubuntu;

use InvalidArgumentException;
use SendCode\Ubuntu\Contracts\ServerInterface;

class Server implements ServerInterface
{
    private string $ipAddress;
    private int $sshPort;
    private string $keyFile;

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * @throws InvalidArgumentException When the given IP address is invalid.
     */
    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getSshPort(): int
    {
        return $this->sshPort;
    }

    /**
     * @throws InvalidArgumentException When the given SSH port is invalid.
     */
    public function setSshPort(int $sshPort): self
    {
        if ($sshPort > 65535) {
            throw new InvalidArgumentException("$sshPort is invalid.");
        }
        $this->sshPort = $sshPort;

        return $this;
    }

    public function getKeyFile(): string
    {
        return $this->keyFile;
    }

    /**
     * @throws InvalidArgumentException When the key file is not a file or readable.
     */
    public function setKeyFile(string $keyFile): self
    {
        if (!is_file($keyFile) || !is_readable($keyFile)) {
            throw new InvalidArgumentException("$keyFile is not a file or not readable.");
        }

        $this->keyFile = $keyFile;

        return $this;
    }

    public function sshAs(string $systemUser): Connection
    {
        return new Connection($this, $systemUser);
    }
}