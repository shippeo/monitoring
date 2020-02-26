<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

class CliContext
{
    /** @var null|Command */
    private $command;
    /** @var int */
    private $exitCode;

    public function __construct(?Command $command, int $exitCode)
    {
        $this->command = $command;
        $this->exitCode = $exitCode;
    }

    public static function fromTerminateEvent(ConsoleTerminateEvent $event): self
    {
        return new self($event->getCommand(), $event->getExitCode());
    }

    public function command(): ?Command
    {
        return $this->command;
    }

    public function exitCode(): int
    {
        return $this->exitCode;
    }
}
