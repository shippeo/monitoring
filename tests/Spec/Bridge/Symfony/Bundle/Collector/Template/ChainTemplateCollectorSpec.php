<?php

declare(strict_types=1);

namespace  Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\ChainTemplateCollector;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\TemplateCollectorInterface;
use Shippeo\Heimdall\Domain\Metric\Template\Template;

class ChainTemplateCollectorSpec extends ObjectBehavior
{
    function let(TemplateCollectorInterface $collector1, TemplateCollectorInterface $collector2)
    {
        $this->beConstructedWith([$collector1, $collector2]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChainTemplateCollector::class);
    }

    function it_implements_TemplateCollectorInterface()
    {
        $this->shouldImplement(TemplateCollectorInterface::class);
    }

    function it_returns_http_templates_collection(
        HTTPContext $httpContext,
        TemplateCollectorInterface $collector1,
        TemplateCollectorInterface $collector2,
        Template $template1,
        Template $template2,
        Template $template3
    ) {
        $collector1->http($httpContext)->willReturn([$template1, $template2])->shouldBeCalledOnce();
        $collector2->http($httpContext)->willReturn([$template3])->shouldBeCalledOnce();

        $this->http($httpContext)->shouldBeLike([$template1, $template2, $template3]);
    }

    function it_returns_cli_templates_collection(
        CliContext $cliContext,
        TemplateCollectorInterface $collector1,
        TemplateCollectorInterface $collector2,
        Template $template1,
        Template $template2,
        Template $template3
    ) {
        $collector1->cli($cliContext)->willReturn([$template1, $template2])->shouldBeCalledOnce();
        $collector2->cli($cliContext)->willReturn([$template3])->shouldBeCalledOnce();

        $this->cli($cliContext)->shouldBeLike([$template1, $template2, $template3]);
    }
}
