<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric;

interface Metric
{
    /**
     * The name of the measurement.
     */
    public function key(): string;

    /**
     * The value to associate.
     */
    public function value(): int;

    /**
     * All extra informations.
     */
    public function tags(): array;
}
