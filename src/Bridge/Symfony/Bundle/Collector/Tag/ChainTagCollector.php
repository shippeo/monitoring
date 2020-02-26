<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;

class ChainTagCollector implements TagCollectorInterface
{
    /** @var iterable|TagCollectorInterface[] */
    private $collectors;

    /** @param iterable|TagCollectorInterface[] $collectors */
    public function __construct(iterable $collectors)
    {
        $this->collectors = $collectors;
    }

    public function http(HTTPContext $context): array
    {
        $tags = [];

        foreach ($this->collectors as $collector) {
            $tags = \array_merge($tags, $collector->http($context));
        }

        return $tags;
    }

    public function cli(CliContext $context): array
    {
        $tags = [];

        foreach ($this->collectors as $collector) {
            $tags = \array_merge($tags, $collector->cli($context));
        }

        return $tags;
    }
}
