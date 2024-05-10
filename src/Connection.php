<?php

namespace SendCode\Ubuntu;

use InvalidArgumentException;
use SendCode\Ubuntu\Contracts\ConnectionInterface;
use SendCode\Ubuntu\Contracts\ExceptionInterface;
use SendCode\Ubuntu\Exceptions\FailedException;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;

class Connection implements ConnectionInterface
{
    private string $user;
    private string $host;
    private int $port;
    private string $keyFile;
    private string $logFile;

    public function __construct()
    {
        //
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @throws InvalidArgumentException When the given IP address is invalid.
     */
    public function setIpAddress(string $ipAddress): self
    {
        if (! filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new InvalidArgumentException("$ipAddress is an invalid IP address.");
        }
        $this->host = $ipAddress;

        return $this;
    }

    /**
     * @throws InvalidArgumentException When the given port is an invalid TCP port.
     */
    public function setPort(int $port): self
    {
        if ($port > 65535) {
            throw new InvalidArgumentException("$port is an invalid TCP port.");
        }
        $this->port = $port;

        return $this;
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

    /**
     * @throws InvalidArgumentException When the log file does not exist or not readable.
     */
    public function setLogFile(string $logFile): self
    {
        if (! is_file($logFile) || ! is_writable($logFile)) {
            throw new InvalidArgumentException("$logFile does not exist or not readable.");
        }

        $this->logFile = $logFile;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function run(string|array $command): Process
    {
        try {
            $ssh = Ssh::create($this->user, $this->host)
                ->usePort($this->port)
                ->usePrivateKey($this->keyFile)
                ->disablePasswordAuthentication()
                ->disableStrictHostKeyChecking();

            $ssh->onOutput(function ($type, $buffer) {
                $this->writeLog($type, $buffer);
            });


            return $ssh->execute(
                $this->normalize($command)
            );
        } catch (\Exception $ex) {
            if ($ex instanceof ExceptionInterface) {
                throw $ex;
            }

            throw (
                (new FailedException())->setOrigin($ex)
            );
        }
    }

    private function writeLog(string $type, string $buffer): void
    {
        if (isset($this->logFile) && is_file($this->logFile) && is_writable($this->logFile)) {
            file_put_contents($this->logFile, $buffer, FILE_APPEND);
        } elseif ($type === Process::ERR) {
            fwrite(STDERR, $buffer);
        } else {
            fwrite(STDOUT, $buffer);
        }
    }

    /**
     * @param string|string[] $command
     * @return string|string[]
     */
    private function normalize(string|array $command): string|array
    {
        if (is_string($command)) {
            return trim(str_replace("\r", "\n", $command));
        }

        return array_map(function ($line) {
            return trim(str_replace("\r", "\n", $line));
        }, $command);
    }
}