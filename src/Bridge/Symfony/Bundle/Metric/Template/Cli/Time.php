<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Cli;

use Shippeo\Heimdall\Domain\Metric\Tag;
use Shippeo\Heimdall\Domain\Metric\Template\Timer;
use Shippeo\Heimdall\Domain\Metric\Timer\Duration;
use Shippeo\Heimdall\Domain\Metric\Timer\Time as MetricTime;

final class Time implements Timer
{
    /** @var MetricTime */
    private $start;
    /** @var MetricTime */
    private $end;

    public function __construct(MetricTime $start, MetricTime $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function name(): string
    {
        return 'cli.execution_time';
    }

    public function tags(): Tag\NameIterator
    {
        return new Tag\NameIterator(
            [
                new Tag\Name('command'),
                new Tag\Name('exit_code'),
            ]
        );
    }

    public function duration(): Duration
    {
        return Duration::fromTimes($this->start, $this->end);
    }
}
