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

    public function http(HTTPContext $context): array
    {
        if (null === $this->dataCollector) {
            return [];
        }

        $this->collect($context->request(), $context->response());

        return [
            new HTTPTemplate\DatabaseTime((float) $this->dataCollector->getTime()),
            new HTTPTemplate\QueryCount((int) $this->dataCollector->getQueryCount()),
        ];
    }

    public function cli(CliContext $context): array
    {
        if (null === $this->dataCollector) {
            return [];
        }

        $this->collect(new Request(), new Response());

        return [
            new CliTemplate\DatabaseTime((float) $this->dataCollector->getTime()),
            new CliTemplate\QueryCount((int) $this->dataCollector->getQueryCount()),
        ];
    }

    private function collect(Request $request, Response $response): void
    {
        if (null === $this->dataCollector) {
            return;
        }

        if (\Closure::bind(function () {
            return \count($this->data ?? []) === 0;
        }, $this->dataCollector, DoctrineDataCollector::class)()) {
            $this->dataCollector->collect($request, $response);
        }
    }
}
