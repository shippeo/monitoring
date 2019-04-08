<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP;

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
        return 'http.time';
    }

    public function tags(): Tag\NameIterator
    {
        return new Tag\NameIterator(
            [
                new Tag\Name('endpoint'),
                new Tag\Name('status_code'),
                new Tag\Name('user'),
                new Tag\Name('organization'),
            ]
        );
    }

    public function duration(): Duration
    {
        return Duration::fromTimes($this->start, $this->end);
    }
}
