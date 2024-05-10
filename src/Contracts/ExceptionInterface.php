<?php

namespace SendCode\Ubuntu\Contracts;

use Exception;
use Throwable;

interface ExceptionInterface extends Throwable
{
    /**
     * Get the original exception.
     */
    public function getOrigin(): ?Exception;
}