<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Cli;

use Shippeo\Heimdall\Domain\Metric\Tag;
use Shippeo\Heimdall\Domain\Metric\Template\Gauge;
use Shippeo\Heimdall\Domain\Metric\Timer\Duration;

final class DatabaseTime implements Gauge
{
    /** @var Duration */
    private $duration;

    public function __construct(float $durationInSeconds)
    {
        $this->duration = new Duration($durationInSeconds);
    }

    public function value(): float
    {
        return $this->duration->asMilliseconds();
    }

    public function name(): string
    {
        return 'cli.database.query_time';
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
