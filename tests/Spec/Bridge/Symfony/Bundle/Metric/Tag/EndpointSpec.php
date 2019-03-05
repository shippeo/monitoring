<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;

final class EndpointSpec extends ObjectBehavior
{
    private $endpoint = 'fakeEndpoint';

    function let()
    {
        $this->beConstructedWith($this->endpoint);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Endpoint::class);
    }

    function it_implements_Tag()
    {
        $this->shouldImplement(Tag::class);
    }

    function it_returns_the_expected_name()
    {
        $this->name()->shouldBeLike(new Name('endpoint'));
    }

    function it_returns_the_value()
    {
        $this->value()->shouldBe($this->endpoint);
    }
}
