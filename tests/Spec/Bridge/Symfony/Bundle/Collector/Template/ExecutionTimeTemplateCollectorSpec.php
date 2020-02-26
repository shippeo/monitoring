<?php

declare(strict_types=1);

namespace  Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\ExecutionTimeTemplateCollector;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\TemplateCollectorInterface;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Cli as CliTemplate;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP as HTTPTemplate;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExecutionTimeTemplateCollectorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ExecutionTimeTemplateCollector::class);
    }

    function it_implements_TemplateCollectorInterface()
    {
        $this->shouldImplement(TemplateCollectorInterface::class);
    }

    function it_implements_EventSubscriberInterface()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_subscribes_to_the_expected_events()
    {
        $this::getSubscribedEvents()
            ->shouldBe(
                [
                    ConsoleEvents::COMMAND => 'initialize',
                    KernelEvents::REQUEST => [
                        ['initialize', 1000],
                    ],
                ]
            )
        ;
    }

    function it_returns_empty_templates_collection_when_the_time_is_not_initialized(
        HTTPContext $httpContext,
        CliContext $cliContext
    ) {
        $this->http($httpContext)->shouldBe([]);
        $this->cli($cliContext)->shouldBe([]);
    }

    function it_returns_execution_time_template(HTTPContext $httpContext, CliContext $cliContext)
    {
        $this->initialize();

        $tags = $this->http($httpContext);
        $tags->shouldHaveCount(1);
        $tags[0]->shouldBeAnInstanceOf(HTTPTemplate\Time::class);

        $tags = $this->cli($cliContext);
        $tags->shouldHaveCount(1);
        $tags[0]->shouldBeAnInstanceOf(CliTemplate\Time::class);
    }
}
