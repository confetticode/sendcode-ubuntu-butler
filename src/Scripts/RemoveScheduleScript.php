<?php

namespace SendCode\Ubuntu\Scripts;

use SendCode\Ubuntu\Contracts\ConnectionInterface;
use SendCode\Ubuntu\Contracts\ScheduleInterface;
use SendCode\Ubuntu\Contracts\ScriptInterface;
use SendCode\Ubuntu\Exceptions\FailedException;
use Symfony\Component\Process\Process;

class RemoveScheduleScript implements ScriptInterface
{
    public function __construct(private ConnectionInterface $connection, private ScheduleInterface $schedule)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function run(): Process
    {
        $process = $this->connection->run("sudo rm -f " . $this->getConfigFilePath());

        if ($process->isSuccessful()) {
            return $process;
        }

        throw new FailedException;
    }

    private function getConfigFilePath(): string
    {
        return "/etc/cron.d/sendcode-schedule-" . $this->schedule->getId();
    }
}