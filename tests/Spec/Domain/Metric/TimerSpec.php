<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric;

use Fake\Tag;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;
use Shippeo\Heimdall\Domain\Metric\Timer;

final class TimerSpec extends ObjectBehavior
{
    /** @var string */
    private $key = 'fakeKey';
    /** @var Timer\Duration */
    private $duration;
    /** @var TagIterator */
    private $tags;

    function let()
    {
        $this->duration = new Timer\Duration(123456.0789);
        $this->tags = new TagIterator([new Tag(), new Tag()]);

        $this->beConstructedWith($this->key, $this->duration, $this->tags);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Timer::class);
    }

    function it_returns_the_key()
    {
        $this->key()->shouldBe($this->key);
    }

    function it_returns_the_value()
    {
        $this
            ->value()
            ->shouldBe(
                $this->duration->asMilliseconds()
            )
        ;
    }

    function it_returns_the_tags()
    {
        $this->tags()->shouldBe($this->tags);
    }
}
