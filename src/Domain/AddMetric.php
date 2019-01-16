<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain;

use Shippeo\Heimdall\Domain\Database\Database;
use Shippeo\Heimdall\Domain\Database\DatabaseIterator;
use Shippeo\Heimdall\Domain\Metric\Metric;

final class AddMetric
{
    /** @var DatabaseIterator */
    private $databases;

    public function __construct(DatabaseIterator $databases)
    {
        $this->databases = $databases;
    }

    public function __invoke(Metric $metric): void
    {
        /** @var Database $database */
        foreach ($this->databases as $database) {
            $database->add($metric);
        }
    }
}
