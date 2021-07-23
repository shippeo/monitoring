<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Cli as CliTemplate;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP as HTTPTemplate;
use Shippeo\Heimdall\Domain\Metric\Timer\Time;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExecutionTimeTemplateCollector implements TemplateCollectorInterface, EventSubscriberInterface
{
    /** @var null|Time */
    private $startTime;

    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => 'initialize',
            KernelEvents::REQUEST => [
                ['initialize', 1000],
            ],
        ];
    }

    public function initialize(): void
    {
        $this->startTime = Time::now();
    }

    public function http(HTTPContext $context): array
    {
        if (null === $this->startTime) {
            return [];
        }

        return [
            new HTTPTemplate\Time($this->startTime, Time::now()),
        ];
    }

    public function cli(CliContext $context): array
    {
        if (null === $this->startTime) {
            return [];
        }

        return [
            new CliTemplate\Time($this->startTime, Time::now()),
        ];
    }
}
