<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP;

use Shippeo\Heimdall\Domain\Metric\Tag;
use Shippeo\Heimdall\Domain\Metric\Template\Timer;
use Shippeo\Heimdall\Domain\Metric\Timer\Duration;

final class DatabaseTime implements Timer
{
    /** @var float */
    private $duration;

    public function __construct(float $duration)
    {
        $this->duration = $duration;
    }

    public function duration(): Duration
    {
        return new Duration($this->duration);
    }

    public function name(): string
    {
        return 'api.database';
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
