<?php

namespace SendCode\Ubuntu\Contracts;

interface ServerInterface
{
    public function getIpAddress(): string;

    public function getSshPort(): int;

    public function getKeyFile(): string;
}
