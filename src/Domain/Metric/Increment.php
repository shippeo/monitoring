<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric;

abstract class Increment implements Metric
{
    public function value(): int
    {
        return 1;
    }
}
