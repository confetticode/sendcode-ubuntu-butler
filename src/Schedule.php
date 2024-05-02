<?php

namespace SendCode\Ubuntu;

use SendCode\Ubuntu\Contracts\ScheduleInterface;

class Schedule implements ScheduleInterface
{
    private int|string $id;
    private string $name;
    private string $expression;
    private string $systemUser;
    private string $command;

    public function __construct(int|string $id, string $name, string $expression, string $systemUser, string $command)
    {
        $this->id = $id;
        $this->name = $name;
        $this->expression = $expression;
        $this->systemUser = $systemUser;
        $this->command = $command;
    }

    public function getId(): int|string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCronExpression(): string
    {
        return $this->expression;
    }

    public function getSystemUser(): string
    {
        return $this->systemUser;
    }

    public function getCommand(): string
    {
        return $this->command;
    }
}