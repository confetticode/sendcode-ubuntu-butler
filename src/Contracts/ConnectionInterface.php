<?php

namespace SendCode\Ubuntu\Contracts;

use Exception;
use Symfony\Component\Process\Process;

interface ConnectionInterface
{
    /**
     * @param string|array $command
     * @throws Exception
     */
    public function run(string|array $command): Process;
}