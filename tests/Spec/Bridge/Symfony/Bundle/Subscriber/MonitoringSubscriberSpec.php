<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag\TagCollectorInterface;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\TemplateCollectorInterface;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber\AutomaticMonitoringDisablerInterface;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber\MonitoringSubscriber;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;
use Shippeo\Heimdall\Domain\Metric\Template\Template;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

final class MonitoringSubscriberSpec extends ObjectBehavior
{
    function let(
        AddMetric $addMetric,
        TagCollectorInterface $tagCollector,
        TemplateCollectorInterface $templateCollector
    ) {
        $this->beConstructedWith($addMetric, $tagCollector, $templateCollector);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MonitoringSubscriber::class);
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
                    KernelEvents::TERMINATE => 'onKernelTerminate',
                    ConsoleEvents::TERMINATE => 'onConsoleTerminate',
                ]
            )
        ;
    }

    function it_sends_metric_on_kernel_terminate(
        AddMetric $addMetric,
        TagCollectorInterface $tagCollector,
        TemplateCollectorInterface $templateCollector,
        KernelInterface $kernel,
        Template $template1,
        Template $template2,
        Tag $tag1,
        Tag $tag2
    ) {
        $request = Request::create('/foo/bar');
        $response = new Response();

        $event = new TerminateEvent($kernel->getWrappedObject(), $request, $response);

        $tagCollector->http(Argument::type(HTTPContext::class))->willReturn([$tag1, $tag2])->shouldBeCalled();
        $templateCollector->http(Argument::type(HTTPContext::class))->willReturn([$template1, $template2])->shouldBeCalled();
        $tagCollection = new TagCollection([$tag1->getWrappedObject(), $tag2->getWrappedObject()]);

        $addMetric->__invoke($template1, $tagCollection)->shouldBeCalledOnce();
        $addMetric->__invoke($template2, $tagCollection)->shouldBeCalledOnce();

        $this->onKernelTerminate($event);
    }

    function it_sends_metric_on_console_terminate(
        AddMetric $addMetric,
        TagCollectorInterface $tagCollector,
        TemplateCollectorInterface $templateCollector,
        Command $command,
        InputInterface $input,
        OutputInterface $output,
        Template $template1,
        Template $template2,
        Tag $tag1,
        Tag $tag2
    ) {
        $event = new ConsoleTerminateEvent($command->getWrappedObject(), $input->getWrappedObject(), $output->getWrappedObject(), 0);

        $tagCollector->cli(Argument::type(CliContext::class))->willReturn([$tag1, $tag2])->shouldBeCalled();
        $templateCollector->cli(Argument::type(CliContext::class))->willReturn([$template1, $template2])->shouldBeCalled();
        $tagCollection = new TagCollection([$tag1->getWrappedObject(), $tag2->getWrappedObject()]);

        $addMetric->__invoke($template1, $tagCollection)->shouldBeCalledOnce();
        $addMetric->__invoke($template2, $tagCollection)->shouldBeCalledOnce();

        $this->onConsoleTerminate($event);
    }

    function it_does_not_send_metrics_when_automatic_monitoring_is_disabled_for_the_command(Command $command)
    {
        $command->implement(AutomaticMonitoringDisablerInterface::class);
        $command->isAutomaticMonitoringDisabled()->willReturn(true);
    }

    function it_sends_metrics_when_automatic_monitoring_is_enabled_for_the_command(
        AddMetric $addMetric,
        TagCollectorInterface $tagCollector,
        TemplateCollectorInterface $templateCollector,
        Command $command,
        InputInterface $input,
        OutputInterface $output,
        Template $template1,
        Template $template2,
        Tag $tag1,
        Tag $tag2
    ) {
        $command->implement(AutomaticMonitoringDisablerInterface::class);
        $command->isAutomaticMonitoringDisabled()->willReturn(false);

        $event = new ConsoleTerminateEvent($command->getWrappedObject(), $input->getWrappedObject(), $output->getWrappedObject(), 0);

        $tagCollector->cli(Argument::type(CliContext::class))->willReturn([$tag1, $tag2])->shouldBeCalled();
        $templateCollector->cli(Argument::type(CliContext::class))->willReturn([$template1, $template2])->shouldBeCalled();
        $tagCollection = new TagCollection([$tag1->getWrappedObject(), $tag2->getWrappedObject()]);

        $addMetric->__invoke($template1, $tagCollection)->shouldBeCalledOnce();
        $addMetric->__invoke($template2, $tagCollection)->shouldBeCalledOnce();

        $this->onConsoleTerminate($event);
    }
}
