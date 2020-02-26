<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;

class ChainTemplateCollector implements TemplateCollectorInterface
{
    /** @var iterable|TemplateCollectorInterface[] */
    private $collectors;

    /** @param iterable|TemplateCollectorInterface[] $collectors */
    public function __construct(iterable $collectors)
    {
        $this->collectors = $collectors;
    }

    public function http(HTTPContext $context): array
    {
        $templates = [];

        foreach ($this->collectors as $collector) {
            $templates = \array_merge($templates, $collector->http($context));
        }

        return $templates;
    }

    public function cli(CliContext $context): array
    {
        $templates = [];

        foreach ($this->collectors as $collector) {
            $templates = \array_merge($templates, $collector->cli($context));
        }

        return $templates;
    }
}
