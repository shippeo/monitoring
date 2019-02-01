<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Database;

use Shippeo\Heimdall\Domain\Metric\Metric;

interface Database
{
    public function store(Metric $metric): void;
}
