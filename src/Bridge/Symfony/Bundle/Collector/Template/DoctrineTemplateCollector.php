<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template;

use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Cli as CliTemplate;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP as HTTPTemplate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctrineTemplateCollector implements TemplateCollectorInterface
{
    /** @var null|DoctrineDataCollector */
    private $dataCollector;

    public function __construct(?DoctrineDataCollector $dataCollector)
    {
        $this->dataCollector = $dataCollector;
    }

    /** {@inheritDoc} */
    public function http(HTTPContext $context): array
    {
        if (null === $this->dataCollector) {
            return [];
        }

        /** @var DoctrineDataCollector $dataCollector */
        $dataCollector = $this->dataCollector;
        $this->collect($context->request(), $context->response());

        return [
            new HTTPTemplate\DatabaseTime((float) $dataCollector->getTime()),
            new HTTPTemplate\QueryCount($dataCollector->getQueryCount()),
        ];
    }

    /** {@inheritDoc} */
    public function cli(CliContext $context): array
    {
        if (null === $this->dataCollector) {
            return [];
        }

        /** @var DoctrineDataCollector $dataCollector */
        $dataCollector = $this->dataCollector;
        $this->collect(new Request(), new Response());

        return [
            new CliTemplate\DatabaseTime((float) $dataCollector->getTime()),
            new CliTemplate\QueryCount($dataCollector->getQueryCount()),
        ];
    }

    private function collect(Request $request, Response $response): void
    {
        if (null === $this->dataCollector) {
            return;
        }

        /** @var bool $isDataCollected */
        $isDataCollected = \Closure::bind(function (): bool {
            /**  @phpstan-ignore-next-line */
            return \count($this->data ?? []) > 0;
        }, $this->dataCollector, DoctrineDataCollector::class)();

        if (!$isDataCollected) {
            $this->dataCollector->collect($request, $response);
        }
    }
}
