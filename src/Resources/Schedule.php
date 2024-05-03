<?php

namespace SendCode\Ubuntu\Resources;

use SendCode\Ubuntu\Contracts\ScheduleInterface;
use Stringable;

class Schedule implements ScheduleInterface, Stringable
{
    private int|string $id;
    private string $name;
    private string $cronExpression;
    private string $systemUser;
    private string $command;

    public function getId(): int|string
    {
        return $this->id;
    }

    public function setId(int|string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCronExpression(): string
    {
        return $this->cronExpression;
    }

    public function setCronExpression(string $cronExpression): self
    {
        $this->cronExpression = $cronExpression;

        return $this;
    }

    public function getSystemUser(): string
    {
        return $this->systemUser;
    }

    public function setSystemUser(string $systemUser): self
    {
        $this->systemUser = $systemUser;

        return $this;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function setCommand(string $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function __toString(): string
    {
        return "# $this->id - $this->name\n$this->cronExpression $this->systemUser $this->command";
    }
}