<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber;

use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag\TagCollectorInterface;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\TemplateCollectorInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class MonitoringSubscriber implements EventSubscriberInterface
{
    /** @var AddMetric */
    private $addMetric;
    /** @var TagCollectorInterface */
    private $tagCollector;
    /** @var TemplateCollectorInterface */
    private $templateCollector;

    public function __construct(
        AddMetric $addMetric,
        TagCollectorInterface $tagCollector,
        TemplateCollectorInterface $templateCollector
    ) {
        $this->addMetric = $addMetric;
        $this->tagCollector = $tagCollector;
        $this->templateCollector = $templateCollector;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => 'onKernelTerminate',
            ConsoleEvents::TERMINATE => 'onConsoleTerminate',
        ];
    }

    public function onKernelTerminate(PostResponseEvent $event): void
    {
        $context = HTTPContext::fromTerminateEvent($event);
        $tags = new TagCollection($this->tagCollector->http($context));

        foreach ($this->templateCollector->http($context) as $template) {
            ($this->addMetric)($template, $tags);
        }
    }

    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        $command = $event->getCommand();

        if (null === $command || $command instanceof AutomaticMonitoringDisablerInterface && $command->isAutomaticMonitoringDisabled()) {
            return;
        }

        $context = CliContext::fromTerminateEvent($event);
        $tags = new TagCollection($this->tagCollector->cli($context));

        foreach ($this->templateCollector->cli($context) as $template) {
            ($this->addMetric)($template, $tags);
        }
    }
}
