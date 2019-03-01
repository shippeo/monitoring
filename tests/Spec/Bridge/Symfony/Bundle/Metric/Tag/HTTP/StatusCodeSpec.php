<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode as Code;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP\StatusCode;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;

final class StatusCodeSpec extends ObjectBehavior
{
    /** @var Code */
    private $code;

    function let()
    {
        $this->code = new Code(123);

        $this->beConstructedWith($this->code);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StatusCode::class);
    }

    function it_implements_Tag()
    {
        $this->shouldImplement(Tag::class);
    }

    function it_returns_the_expected_name()
    {
        $this->name()->shouldBeLike(new Name('status_code'));
    }

    function it_returns_the_expected_value()
    {
        $this->value()->shouldBe((string) $this->code->value());
    }
}
