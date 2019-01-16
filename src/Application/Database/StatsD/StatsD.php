<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Database\StatsD;

use Shippeo\Heimdall\Domain\Database\Database;
use Shippeo\Heimdall\Domain\Metric\Increment;
use Shippeo\Heimdall\Domain\Metric\Metric;

final class StatsD implements Database
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function add(Metric $metric): void
    {
        if (!$metric instanceof Increment) {
            throw new \LogicException('not implemented yet');
        }

        $this->client->increment(new Key($metric->key(), $metric->tags()), $metric->value());
    }
}
