<?php

declare(strict_types=1);

namespace Functional\Fake;

use Shippeo\Heimdall\Application\Database\StatsD\Client;
use Shippeo\Heimdall\Application\Database\StatsD\Key;

final class StatsDClient implements Client
{
    public function increment(Key $key, int $value): void
    {
    }
}
