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
        $validTagName = 'name.0123/A-B_C';
        $validTagValue = 'value.0123/A-B_C';
        $invalidTagName = 'name:foo?bar';
        $invalidTagValue = 'value.foo bar';
        $key = '[toto]';

        $tag1 = new Tag($validTagName, $invalidTagValue);
        $tag2 = new Tag($invalidTagName, $validTagValue);

        $this->beConstructedWith($key, new TagIterator([$tag1, $tag2]));

        $this
            ->__toString()
            ->shouldReturn(
                '_toto_,'.$validTagName.'=value.foo_bar,name_foo_bar='.$validTagValue
            )
        ;
    }
}
