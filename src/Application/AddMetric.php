<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application;

use Shippeo\Heimdall\Domain\Database\DatabaseIterator;
use Shippeo\Heimdall\Domain\Metric\Metric;
use Shippeo\Heimdall\Domain\SaveMetric;

final class AddMetric
{
    /** @var SaveMetric */
    private $saveMetric;

    public function __construct(iterable $databases)
    {
        $this->saveMetric = new SaveMetric(new DatabaseIterator($databases));
    }

    public function __invoke(Metric $metric): void
    {
        ($this->saveMetric)($metric);
    }
}
