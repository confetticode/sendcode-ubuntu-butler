<?php

namespace SendCode\Ubuntu;

use SendCode\Ubuntu\Contracts\ConnectionInterface;
use SendCode\Ubuntu\Contracts\ExceptionInterface;
use SendCode\Ubuntu\Contracts\ScriptInterface;
use SendCode\Ubuntu\Contracts\ServerInterface;
use SendCode\Ubuntu\Exceptions\FailedException;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;

class Connection implements ConnectionInterface
{
    private string $logFile;

    public function __construct(private ServerInterface $server, private string $systemUser)
    {
        //
    }

    /**
     * @throws \InvalidArgumentException When the log file does not exist or not readable.
     */
    public function useLogFile(string $logFile): self
    {
        if (! is_file($logFile) || ! is_writable($logFile)) {
            throw new \InvalidArgumentException("$logFile does not exist or not readable.");
        }

        $this->logFile = $logFile;

        return $this;
    }

    private function clearLogFile(): void
    {
        if (isset($this->logFile) && is_file($this->logFile) && is_writable($this->logFile)) {
            // Clean the log file before running.
            file_put_contents($this->logFile, '');
        }
    }

    private function writeLog($type, string $buffer): void
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
     * {@inheritdoc}
     */
    public function run(string|array $command): Process
    {
        $this->clearLogFile(); // Clean the log file before running.

        try {
            $ssh = Ssh::create($this->systemUser, $this->server->getIpAddress())
                ->usePort($this->server->getSshPort())
                ->disablePasswordAuthentication()
                ->disableStrictHostKeyChecking();

            $ssh->onOutput(function ($type, $buffer) {
                $this->writeLog($type, $buffer);
            });

            return $ssh->execute(
                str_replace("\r\n", "\n", $command)
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
}