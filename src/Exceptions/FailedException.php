<?php

namespace SendCode\Ubuntu\Exceptions;

use Exception;
use SendCode\Ubuntu\Contracts\ExceptionInterface;

class FailedException extends \Exception implements ExceptionInterface
{
    private ?Exception $origin;

    /**
     * {@inheritdoc}
     */
    public function getOrigin(): ?Exception
    {
        return $this->origin;
    }

    public function setOrigin(Exception $origin): self
    {
        $this->origin = $origin;

        return $this;
    }
}
