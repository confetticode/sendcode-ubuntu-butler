<?php

namespace SendCode\Ubuntu\Contracts;

use Symfony\Component\Process\Process;

interface ScriptInterface
{
    /**
     * Run this script on a connection.
     *
     * @throws ExceptionInterface when it's failed such as timeout, couldn't add, modify or delete files,...
     */
    public function run(): Process;
}
