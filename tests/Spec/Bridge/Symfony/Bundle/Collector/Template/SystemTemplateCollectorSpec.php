<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\SystemTemplateCollector;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\TemplateCollectorInterface;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Cli as CliTemplate;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP as HTTPTemplate;

class SystemTemplateCollectorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SystemTemplateCollector::class);
    }

    function it_implements_TemplateCollectorInterface()
    {
        $this->shouldImplement(TemplateCollectorInterface::class);
    }

    function it_returns_http_templates_collection(HTTPContext $httpContext)
    {
        $tags = $this->http($httpContext);
        $tags->shouldHaveCount(1);
        $tags[0]->shouldBeAnInstanceOf(HTTPTemplate\MemoryPeak::class);
    }

    function it_returns_cli_templates_collection(CliContext $cliContext)
    {
        $tags = $this->cli($cliContext);
        $tags->shouldHaveCount(1);
        $tags[0]->shouldBeAnInstanceOf(CliTemplate\MemoryPeak::class);
    }
}
