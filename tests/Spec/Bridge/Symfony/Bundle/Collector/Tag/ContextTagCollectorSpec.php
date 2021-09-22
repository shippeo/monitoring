<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag\ContextTagCollector;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag\TagCollectorInterface;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Cli\CommandName as CommandNameTag;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Cli\ExitCode as ExitCodeTag;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint as EndpointTag;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP\StatusCode as StatusCodeTag;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContextTagCollectorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ContextTagCollector::class);
    }

    function it_implements_TagCollectorInterface()
    {
        $this->shouldImplement(TagCollectorInterface::class);
    }

    function it_returns_http_tags_collection_without_the_endpoint(HTTPContext $httpContext)
    {
        $statusCode = 200;
        $route = 'foobar';

        $response = new Response('', $statusCode);
        $request = new Request();
        $httpContext->request()->willReturn($request)->shouldBeCalledOnce();
        $httpContext->response()->willReturn($response)->shouldBeCalledOnce();

        $this->http($httpContext)->shouldBeLike([
            new StatusCodeTag(new StatusCode($statusCode)),
        ]);
    }

    function it_returns_http_tags_collection(HTTPContext $httpContext)
    {
        $statusCode = 200;
        $route = 'foobar';

        $response = new Response('', $statusCode);
        $request = new Request([], [], ['_route' => $route]);
        $httpContext->request()->willReturn($request)->shouldBeCalledOnce();
        $httpContext->response()->willReturn($response)->shouldBeCalledOnce();

        $this->http($httpContext)->shouldBeLike([
            new StatusCodeTag(new StatusCode($statusCode)),
            new EndpointTag($route),
        ]);
    }

    function it_returns_cli_tags_collection(CliContext $cliContext, Command $command)
    {
        $exitCode = 0;
        $name = 'foobar';

        $command->getName()->willReturn($name);
        $cliContext->command()->willReturn($command);
        $cliContext->exitCode()->willReturn($exitCode);

        $this->cli($cliContext)->shouldBeLike([
            new ExitCodeTag($exitCode),
            new CommandNameTag($name),
        ]);
    }
}
