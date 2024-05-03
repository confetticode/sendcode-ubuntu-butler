<?php

namespace SendCode\Ubuntu\Scripts;

use SendCode\Ubuntu\Contracts\ScheduleInterface;
use SendCode\Ubuntu\Contracts\ScriptInterface;

class AddScheduleScript implements ScriptInterface
{
    public function __construct(private ScheduleInterface $schedule)
    {
        //
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