<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric;

use Fake\Tag;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;
use Shippeo\Heimdall\Domain\Metric\Timer;
use Shippeo\Heimdall\Domain\Metric\Timer\Time;

final class TimerSpec extends ObjectBehavior
{
    /** @var string */
    private $key = 'fakeKey';
    /** @var Time */
    private $start;
    /** @var Time */
    private $end;
    /** @var TagIterator */
    private $tags;

    function let()
    {
        $this->start = Time::now();
        $this->end = Time::now();
        $this->tags = new TagIterator([new Tag(), new Tag()]);

        $this->beConstructedWith($this->key, $this->start, $this->end, $this->tags);
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
                \round($this->end->asMilliseconds() - $this->start->asMilliseconds(), 4)
            )
        ;
    }

    function it_returns_the_tags()
    {
        $this->tags()->shouldBe($this->tags);
    }
}
