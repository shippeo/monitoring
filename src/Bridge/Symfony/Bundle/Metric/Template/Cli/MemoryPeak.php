<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Cli;

use Shippeo\Heimdall\Domain\Metric\Tag;
use Shippeo\Heimdall\Domain\Metric\Template\Gauge;

final class MemoryPeak implements Gauge
{
    /** @var int */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function value(): float
    {
        return (float) $this->value;
    }

    public function name(): string
    {
        return 'cli.memory_peak';
    }

    public function tags(): Tag\NameIterator
    {
        return new Tag\NameIterator(
            [
                new Tag\Name('command_name'),
                new Tag\Name('exit_code'),
            ]
        );
    }
}
