<?php

namespace SendCode\Ubuntu\Contracts;

use Exception;
use Symfony\Component\Process\Process;

interface ScriptInterface
{
    /**
     * @param ConnectionInterface $connection
     * @throws Exception
     */
    public function runOn(ConnectionInterface $connection): Process;
}
