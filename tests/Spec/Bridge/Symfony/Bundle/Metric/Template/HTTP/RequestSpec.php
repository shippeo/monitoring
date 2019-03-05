<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP\Request;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;
use Shippeo\Heimdall\Domain\Metric\Template\Counter;
use Shippeo\Heimdall\Domain\Metric\Template\Template;

final class RequestSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Request::class);
    }

    function it_implements_Counter()
    {
        $this->shouldImplement(Counter::class);
    }

    function it_implements_Template()
    {
        $this->shouldImplement(Template::class);
    }

    function it_returns_the_name()
    {
        $this->name()->shouldBe('api.request');
    }

    function it_returns_the_value()
    {
        $this->value()->shouldBe(1);
    }

    function it_returns_the_expected_tags()
    {
        $this
            ->tags()
            ->shouldBeLike(
                new NameIterator(
                    [
                        new Name('endpoint'),
                        new Name('organization'),
                        new Name('user'),
                    ]
                )
            )
        ;
    }
}
