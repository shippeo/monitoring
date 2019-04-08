<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric;

use Fake\Tag;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Gauge;
use Shippeo\Heimdall\Domain\Metric\Metric;
use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;

final class GaugeSpec extends ObjectBehavior
{
    /** @var string */
    private $key = 'fakeKey';
    /** @var float */
    private $value = 654.321;
    /** @var TagIterator */
    private $tags;

    function let()
    {
        $this->tags = new TagIterator([new Tag(), new Tag()]);

        $this->beConstructedWith($this->key, $this->value, $this->tags);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Gauge::class);
    }

    function it_implements_Metric()
    {
        $this->shouldImplement(Metric::class);
    }

    function it_returns_the_key()
    {
        $this->key()->shouldBe($this->key);
    }

    function it_returns_the_value()
    {
        $this->value()->shouldBe($this->value);
    }

    function it_returns_the_tags()
    {
        $this->tags()->shouldBe($this->tags);
    }
}
