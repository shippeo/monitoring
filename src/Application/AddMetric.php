<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application;

use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Domain\Database\DatabaseIterator;
use Shippeo\Heimdall\Domain\Metric\Factory;
use Shippeo\Heimdall\Domain\Metric\Template\Template;
use Shippeo\Heimdall\Domain\SaveMetric;

class AddMetric
{
    /** @var SaveMetric */
    private $saveMetric;
    /** @var Factory */
    private $factory;

    public function __construct(iterable $databases, TagCollection $globalTags)
    {
        $this->saveMetric = new SaveMetric(new DatabaseIterator($databases));
        $this->factory = new Factory($globalTags->getIterator());
    }

    public function __invoke(Template $template, TagCollection $tags): void
    {
        ($this->saveMetric)(
            $this->factory->create($template, $tags->getIterator())
        );
    }
}
