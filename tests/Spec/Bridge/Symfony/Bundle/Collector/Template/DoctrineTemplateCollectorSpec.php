<?php

declare(strict_types=1);

namespace  Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template;

use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\DoctrineTemplateCollector;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\TemplateCollectorInterface;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Cli as CliTemplate;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP as HTTPTemplate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctrineTemplateCollectorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(null);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineTemplateCollector::class);
    }

    function it_implements_TemplateCollectorInterface()
    {
        $this->shouldImplement(TemplateCollectorInterface::class);
    }

    function it_returns_empty_templates_collection_when_no_data_collector_is_set(
        HTTPContext $httpContext,
        CliContext $cliContext
    ) {
        $this->http($httpContext)->shouldBe([]);
        $this->cli($cliContext)->shouldBe([]);
    }

    function it_returns_http_templates_collection(
        HTTPContext $httpContext,
        DoctrineDataCollector $dataCollector,
        Request $request,
        Response $response
    ) {
        $time = 10.0;
        $queryCount = 5;

        $dataCollector->getTime()->willReturn($time);
        $dataCollector->getQueryCount()->willReturn($queryCount);
        $dataCollector->collect($request, $response)->shouldBeCalledOnce();

        $httpContext->request()->willReturn($request);
        $httpContext->response()->willReturn($response);

        $this->beConstructedWith($dataCollector);
        $this->http($httpContext)->shouldBeLike([
            new HTTPTemplate\DatabaseTime($time),
            new HTTPTemplate\QueryCount($queryCount),
        ]);
    }

    function it_returns_cli_templates_collection(CliContext $cliContext, DoctrineDataCollector $dataCollector)
    {
        $time = 10.0;
        $queryCount = 5;

        $dataCollector->getTime()->willReturn($time);
        $dataCollector->getQueryCount()->willReturn($queryCount);
        $dataCollector->collect(Argument::type(Request::class), Argument::type(Response::class))->shouldBeCalledOnce();

        $this->beConstructedWith($dataCollector);
        $this->cli($cliContext)->shouldBeLike([
            new CliTemplate\DatabaseTime($time),
            new CliTemplate\QueryCount($queryCount),
        ]);
    }
}
