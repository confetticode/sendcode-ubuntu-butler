<?php

namespace SendCode\Ubuntu\Contracts;

interface ScriptInterface
{
    /**
     * Get the compiled bash script to be executed on the server.
     *
     * @return string
     */
    public function compile(): string;
}
