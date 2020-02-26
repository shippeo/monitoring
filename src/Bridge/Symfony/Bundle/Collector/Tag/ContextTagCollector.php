<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Cli\CommandName;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Cli\ExitCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP\StatusCode as StatusCodeTag;

class ContextTagCollector implements TagCollectorInterface
{
    public function http(HTTPContext $context): array
    {
        $request = $context->request();
        $response = $context->response();

        $tags = [
            new StatusCodeTag(
                new StatusCode($response->getStatusCode())
            ),
        ];

        /** @var null|string $endpoint */
        $endpoint = $request->attributes->get('_route');

        if ($endpoint !== null) {
            $tags[] = new Endpoint($endpoint);
        }

        return $tags;
    }

    public function cli(CliContext $context): array
    {
        $tags = [
            new ExitCode($context->exitCode()),
        ];

        $command = $context->command();

        if (null !== $command) {
            $tags[] = new CommandName($command->getName());
        }

        return $tags;
    }
}
