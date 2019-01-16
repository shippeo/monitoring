<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Database\StatsD;

interface Client
{
    public function increment(Key $key, int $value): void;
}
