<?php

namespace SendCode\Ubuntu\Contracts;

interface ScheduleInterface
{
    public function getId(): int|string;

    public function getName(): string;

    public function getSystemUser(): string;

    public function getCronExpression(): string;

    public function getCommand(): string;
}