<?php

declare(strict_types=1);

namespace  Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag\ChainTagCollector;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag\TagCollectorInterface;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;

class ChainTagCollectorSpec extends ObjectBehavior
{
    function let(TagCollectorInterface $collector1, TagCollectorInterface $collector2)
    {
        $this->beConstructedWith([$collector1, $collector2]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChainTagCollector::class);
    }

    function it_implements_TagCollectorInterface()
    {
        $this->shouldImplement(TagCollectorInterface::class);
    }

    function it_returns_http_tags_collection(
        HTTPContext $httpContext,
        TagCollectorInterface $collector1,
        TagCollectorInterface $collector2,
        Tag $tag1,
        Tag $tag2,
        Tag $tag3
    ) {
        $collector1->http($httpContext)->willReturn([$tag1, $tag2])->shouldBeCalledOnce();
        $collector2->http($httpContext)->willReturn([$tag3])->shouldBeCalledOnce();

        $this->http($httpContext)->shouldBeLike([$tag1, $tag2, $tag3]);
    }

    function it_returns_cli_tags_collection(
        CliContext $cliContext,
        TagCollectorInterface $collector1,
        TagCollectorInterface $collector2,
        Tag $tag1,
        Tag $tag2,
        Tag $tag3
    ) {
        $collector1->cli($cliContext)->willReturn([$tag1, $tag2])->shouldBeCalledOnce();
        $collector2->cli($cliContext)->willReturn([$tag3])->shouldBeCalledOnce();

        $this->cli($cliContext)->shouldBeLike([$tag1, $tag2, $tag3]);
    }
}
