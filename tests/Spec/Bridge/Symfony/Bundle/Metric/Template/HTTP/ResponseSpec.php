<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP\Response;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;
use Shippeo\Heimdall\Domain\Metric\Template\Counter;
use Shippeo\Heimdall\Domain\Metric\Template\Template;

final class ResponseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Response::class);
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
        $this->name()->shouldBe('api.response');
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
                        new Name('status_code'),
                        new Name('user'),
                        new Name('organization'),
                    ]
                )
            )
        ;
    }
}
