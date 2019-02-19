<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application;

use Shippeo\Heimdall\Application\Metric\Factory;
use Shippeo\Heimdall\Application\Metric\Template\Template;
use Shippeo\Heimdall\Domain\Database\DatabaseIterator;
use Shippeo\Heimdall\Domain\SaveMetric;

final class AddMetric
{
    /** @var SaveMetric */
    private $saveMetric;
    /** @var Factory */
    private $factory;

    public function __construct(iterable $databases, Factory $factory)
    {
        $this->saveMetric = new SaveMetric(new DatabaseIterator($databases));
        $this->factory = $factory;
    }

    public function __invoke(Template $template): void
    {
        ($this->saveMetric)(
            $this->factory->create($template)
        );
    }
}
