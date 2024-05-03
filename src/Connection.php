<?php

namespace SendCode\Ubuntu;

use SendCode\Ubuntu\Contracts\ScriptInterface;
use SendCode\Ubuntu\Contracts\ServerInterface;
use Spatie\Ssh\Ssh;

class Connection
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
        if (! is_file($logFile) || ! is_readable($logFile)) {
            throw new \InvalidArgumentException("$logFile does not exist or not readable.");
        }

        $this->logFile = $logFile;

        return $this;
    }

    private function clearLogFile(): void
    {
        if (isset($this->logFile) && is_file($this->logFile) && is_readable($this->logFile)) {
            // Clean the log file before running.
            file_put_contents($this->logFile, '');
        }
    }

    private function writeToLogFile(string $buffer): void
    {
        if (isset($this->logFile) && is_file($this->logFile) && is_readable($this->logFile)) {
            file_put_contents($this->logFile, $buffer, FILE_APPEND);
        }
    }

    public function run(string $command): RunResult
    {
        $this->clearLogFile(); // Clean the log file before running.

        $ssh = Ssh::create($this->systemUser, $this->server->getIpAddress())
            ->usePort($this->server->getSshPort())
            ->disablePasswordAuthentication()
            ->disableStrictHostKeyChecking();

        if (isset($this->logFile) && is_file($this->logFile) && is_readable($this->logFile)) {
            $ssh->onOutput(function ($type, $buffer) {
                $this->writeToLogFile($buffer);
            });
        }

        $process = $ssh->execute($command);

        if (isset($this->logFile) && is_file($this->logFile) && is_readable($this->logFile)) {
            $log = trim(file_get_contents($this->logFile));
        }

        $result = new RunResult($process, $log);

        $this->clearLogFile(); // Clean the log file after running.

        return $result;
    }

    public function runScript(ScriptInterface $script): RunResult
    {
        return $this->run($script->compile());
    }
}