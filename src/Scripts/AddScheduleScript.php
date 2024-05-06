<?php

namespace SendCode\Ubuntu\Scripts;

use SendCode\Ubuntu\Contracts\ConnectionInterface;
use SendCode\Ubuntu\Contracts\ScheduleInterface;
use SendCode\Ubuntu\Contracts\ScriptInterface;
use Symfony\Component\Process\Process;

class AddScheduleScript implements ScriptInterface
{
    public function __construct(private ScheduleInterface $schedule)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function runOn(ConnectionInterface $connection): Process
    {
        $process = $connection->run(
            $this->compile()
        );

        if (!$process->isSuccessful()) {
            throw new \RuntimeException("Failed to add schedule.");
        }

        return $process;
    }

    public function compile(): string
    {
        $id = $this->schedule->getId();
        $name = $this->schedule->getName();
        $cronExpression = $this->schedule->getCronExpression();
        $systemUser = $this->schedule->getSystemUser();
        $command = $this->schedule->getCommand();

        $file = "/etc/cron.d/sendcode-schedule-" . $this->schedule->getId();
        $config = "# $id - $name\n$cronExpression $systemUser $command";

        return <<< "EOL"
sudo su

if [ -f $file ]; then
    echo \"$file already exists\"
    
    exit 1
fi

echo "$config" > $file

cat $file

exit 0
EOL;
    }
}