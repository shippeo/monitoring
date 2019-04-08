<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP;

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
        return 'http.database';
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
}
