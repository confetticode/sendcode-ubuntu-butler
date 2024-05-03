<?php

namespace SendCode\Ubuntu;

use Symfony\Component\Process\Process;

class RunResult
{
    public function __construct(private Process $process, private string $log)
    {
    }

    public function isSuccessful(): bool
    {
        return $this->process->isSuccessful();
    }

    public function isFailed(): bool
    {
        return ! $this->isSuccessful();
    }

    public function getLog(): string
    {
        return $this->log;
    }
}