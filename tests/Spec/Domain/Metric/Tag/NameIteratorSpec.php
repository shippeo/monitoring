<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric\Tag;

use Fake\Tag;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;

final class NameIteratorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NameIterator::class);
    }

    function it_implements_IteratorAggregate()
    {
        $this->shouldImplement(\IteratorAggregate::class);
    }

    function it_throws_an_exception_when_not_all_instance_of_Tag()
    {
        $this->beConstructedWith([(new Tag\Tag1())->name(), 'notATag', (new Tag\Tag2())->name()]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith([(new Tag\Tag1())->name(), 123456, (new Tag\Tag2())->name()]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith([(new Tag\Tag1())->name(), ['notATag'], (new Tag\Tag2())->name()]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith([(new Tag\Tag1())->name(), new \stdClass(), (new Tag\Tag2())->name()]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_works_as_an_iterator()
    {
        $tag1 = (new Tag\Tag1())->name();
        $tag2 = (new Tag\Tag2())->name();

        $this->beConstructedWith([$tag1, $tag2]);

        $this->shouldIterateAs([$tag1, $tag2]);
    }
}
