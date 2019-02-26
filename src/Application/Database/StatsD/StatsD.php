<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Database\StatsD;

use Shippeo\Heimdall\Domain\Database\Database;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Metric;
use Shippeo\Heimdall\Domain\Metric\Timer;

final class StatsD implements Database
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function store(Metric $metric): void
    {
        $key = new Key($metric->key(), $metric->tags());
        if ($metric instanceof Counter) {
            $this->client->increment($key, $metric->value());
        } elseif ($metric instanceof Timer) {
            $this->client->timing($key, $metric->value());
        } else {
            throw new \LogicException('not implemented yet');
        }
    }
}
