<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric\Tag;

use Fake\Tag;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;

final class TagIteratorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TagIterator::class);
    }

    function it_implements_Iterator()
    {
        $this->shouldImplement(\Iterator::class);
    }

    function it_throws_an_exception_when_not_all_instance_of_Tag()
    {
        $this->beConstructedWith([new Tag(), 'notATag', new Tag()]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith([new Tag(), 123456, new Tag()]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith([new Tag(), ['notATag'], new Tag()]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith([new Tag(), new \stdClass(), new Tag()]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_works_as_an_iterator()
    {
        $tag1 = new Tag();
        $tag2 = new Tag();

        $this->beConstructedWith([$tag1, $tag2]);

        $this->valid()->shouldBe(true);
        $this->key()->shouldBe(0);
        $this->current()->shouldBe($tag1);
        $this->next();
        $this->valid()->shouldBe(true);
        $this->key()->shouldBe(1);
        $this->current()->shouldBe($tag2);
        $this->next();
        $this->valid()->shouldBe(false);
        $this->rewind();
        $this->valid()->shouldBe(true);
        $this->current()->shouldBe($tag1);
    }
}
