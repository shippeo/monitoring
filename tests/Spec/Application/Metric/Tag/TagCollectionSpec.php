<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Application\Metric\Tag;

use Fake\Tag;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Application\Util\TypedCollection;
use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;

final class TagCollectionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TagCollection::class);
    }

    function it_extends_TypedCollection()
    {
        $this->shouldHaveType(TypedCollection::class);
    }

    function it_implements_ArrayAccess()
    {
        $this->shouldImplement(\ArrayAccess::class);
    }

    function it_implements_IteratorAggregate()
    {
        $this->shouldImplement(\IteratorAggregate::class);
    }

    function it_throws_an_exception_when_initializing_with_elements_not_being_tags()
    {
        $this->beConstructedWith([12.3]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith([12]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith(['invalidString']);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith([[]]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith([new \stdClass()]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_initialize_with_tag_elements()
    {
        $this->beConstructedWith([new Tag(), new Tag()]);

        $this->shouldHaveType(TagCollection::class);
    }

    function it_throws_an_exception_when_trying_to_add_elements_not_being_a_tag()
    {
        $offset = 0;

        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('offsetSet', [$offset, 12.3])
        ;
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('offsetSet', [$offset, 12])
        ;
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('offsetSet', [$offset, 'invalidString'])
        ;
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('offsetSet', [$offset, []])
        ;
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('offsetSet', [$offset, new \stdClass()])
        ;
    }

    function it_adds_and_removes_tag()
    {
        $offset = 0;
        $tag = new Tag();

        $this->offsetExistsInCollection($offset)->shouldBe(false);
        $this->offsetSet($offset, $tag);
        $this->offsetExistsInCollection($offset)->shouldBe(true);
        $this->offsetGet($offset)->shouldBe($tag);
        $this->offsetUnset($offset);
        $this->offsetExistsInCollection($offset)->shouldBe(false);
    }

    function it_returns_the_tags_as_an_array()
    {
        $tag1 = new Tag();
        $tag2 = new Tag();
        $tag3 = new Tag();

        $this->beConstructedWith([$tag1, $tag2]);
        $this->offsetSet(2, $tag3);

        $this
            ->toArray()
            ->shouldBe(
                [
                    $tag1,
                    $tag2,
                    $tag3,
                ]
            )
        ;
    }

    function it_returns_the_tags_in_a_specific_iterator()
    {
        $tag1 = new Tag();
        $tag2 = new Tag();
        $tag3 = new Tag();

        $this->beConstructedWith([$tag1, $tag2]);
        $this->offsetSet(null, $tag3);

        $this
            ->getIterator()
            ->shouldBeLike(
                new TagIterator(
                    [
                        $tag1,
                        $tag2,
                        $tag3,
                    ]
                )
            )
        ;
    }

    function it_creates_another_collection_with_the_actual_tags_and_tags_in_another_collection()
    {
        $tag1 = new Tag();
        $tag2 = new Tag();
        $tag3 = new Tag();
        $tag4 = new Tag();

        $this->beConstructedWith([$tag1, $tag2]);

        $this
            ->mergeWith(new TagCollection([$tag3, $tag4]))
            ->shouldBeLike(
                new TagCollection(
                    [
                        $tag1,
                        $tag2,
                        $tag3,
                        $tag4,
                    ]
                )
            )
        ;
    }
}
