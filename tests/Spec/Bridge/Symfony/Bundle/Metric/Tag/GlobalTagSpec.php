<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\GlobalTag;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;

class GlobalTagSpec extends ObjectBehavior
{
    private $name = 'fakeName';
    private $value = 'fake value';

    function let()
    {
        $this->beConstructedWith($this->name, $this->value);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GlobalTag::class);
    }

    function it_implements_Tag()
    {
        $this->shouldImplement(Tag::class);
    }

    function it_returns_the_name()
    {
        $this->name()->shouldBeLike(new Name($this->name));
    }

    function it_returns_the_value()
    {
        $this->value()->shouldBe($this->value);
    }
}
