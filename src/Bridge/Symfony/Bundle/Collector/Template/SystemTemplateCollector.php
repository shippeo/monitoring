<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Cli as CliTemplate;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP as HTTPTemplate;

class SystemTemplateCollector implements TemplateCollectorInterface
{
    public function http(HTTPContext $context): array
    {
        return [
            new HTTPTemplate\MemoryPeak(\memory_get_peak_usage(true)),
        ];
    }

    public function cli(CliContext $context): array
    {
        return [
            new CliTemplate\MemoryPeak(\memory_get_peak_usage(true)),
        ];
    }
}
