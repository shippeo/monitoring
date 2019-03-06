<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Template;

interface Gauge extends Template
{
    public function value(): int;
}
