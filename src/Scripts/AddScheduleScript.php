<?php

namespace SendCode\Ubuntu\Scripts;

use SendCode\Ubuntu\Contracts\ConnectionInterface;
use SendCode\Ubuntu\Contracts\ScheduleInterface;
use SendCode\Ubuntu\Contracts\ScriptInterface;
use SendCode\Ubuntu\Exceptions\FailedException;
use Symfony\Component\Process\Process;

class AddScheduleScript implements ScriptInterface
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
        $process = $this->connection->run(
            $this->compile()
        );

        if ($process->isSuccessful()) {
            return $process;
        }

        $message = $this->getFileExistsMessage();

        if (str_contains($process->getOutput(), $message) || str_contains($process->getErrorOutput(), $message)) {
            throw new FailedException($message);
        }

        throw new FailedException;
    }

    private function getFileExistsMessage(): string
    {
        return $this->getConfigFilePath()." already exists.";
    }

    private function getConfigFilePath(): string
    {
        return "/etc/cron.d/sendcode-schedule-" . $this->schedule->getId();
    }

    public function compile(): string
    {
        $id = $this->schedule->getId();
        $name = $this->schedule->getName();
        $cronExpression = $this->schedule->getCronExpression();
        $systemUser = $this->schedule->getSystemUser();
        $command = $this->schedule->getCommand();

        $file = $this->getConfigFilePath();
        $message = $this->getFileExistsMessage();
        $config = "# $id - $name\n$cronExpression $systemUser $command";

        return "
sudo su

if [ -f $file ]; then
    echo \"$message\"
    
    exit 1
fi

echo \"$config\" > $file

echo \"Schedule is added at $file.\n\"

cat $file

exit 0
";
    }
}