<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Application\Database\StatsD;

use Fake\Tag;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Application\Database\StatsD\Key;
use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;

final class KeySpec extends ObjectBehavior
{
    private $key = 'fakeKey';

    function let()
    {
        $this->beConstructedWith($this->key, new TagIterator([]));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Key::class);
    }

    function it_returns_the_key_only()
    {
        $this->__toString()->shouldReturn($this->key);
    }

    function it_returns_the_key_with_tags()
    {
        $tag1 = new Tag();
        $tag2 = new Tag();

        $this->beConstructedWith($this->key, new TagIterator([$tag1, $tag2]));

        $this
            ->__toString()
            ->shouldReturn(
                $this->key.','.$tag1->name().'='.$tag1->value().','.$tag2->name().'='.$tag2->value()
            )
        ;
    }
}
