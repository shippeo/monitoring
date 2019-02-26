<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode;

final class StatusCodeSpec extends ObjectBehavior
{
    private $code = 123;

    function let()
    {
        $this->beConstructedWith($this->code);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StatusCode::class);
    }

    function it_returns_the_code()
    {
        $this->value()->shouldBe($this->code);
    }

    function it_throws_an_exception_when_initializing_with_code_under_100()
    {
        $this->beConstructedWith(99);

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_throws_an_exception_when_initializing_with_code_superior_to_599()
    {
        $this->beConstructedWith(600);

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_is_initializable_with_code_at_100()
    {
        $this->beConstructedWith(100);

        $this->shouldNotThrow(\Throwable::class)->duringInstantiation();
    }

    function it_is_initializable_with_code_at_599()
    {
        $this->beConstructedWith(599);

        $this->shouldNotThrow(\Throwable::class)->duringInstantiation();
    }
}
