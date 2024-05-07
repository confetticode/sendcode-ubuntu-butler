<?php

namespace SendCode\Ubuntu\Contracts;

use Symfony\Component\Process\Process;

interface ConnectionInterface
{
    /**
     * Running the given command on a specific server.
     *
     * @throws ExceptionInterface when it's failed to run the given command. Eg: it's timeout or does not have permissions (a regular user tries to run a sudo command).
     */
    public function run(string|array $command): Process;
}