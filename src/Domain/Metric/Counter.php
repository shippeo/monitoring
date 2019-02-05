<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric;

interface Counter extends Metric
{
    /**
     * Returns a positive int to increment and a negative int to decrement.
     */
    public function value(): int;
}
