<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric\Tag;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NullTag;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;

final class NullTagSpec extends ObjectBehavior
{
    /** @var Name */
    private $name;

    function let()
    {
        $this->name = new Name('fakeName');
        $this->beConstructedWith($this->name);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NullTag::class);
    }

    function it_implements_Tag()
    {
        $this->shouldImplement(Tag::class);
    }

    function it_returns_the_name()
    {
        $this->name()->shouldBe($this->name);
    }

    function it_returns_an_empty_value()
    {
        $this->value()->shouldBe('');
    }
}
