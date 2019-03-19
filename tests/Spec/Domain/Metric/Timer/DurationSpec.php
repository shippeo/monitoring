<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric\Timer;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Timer\Duration;
use Shippeo\Heimdall\Domain\Metric\Timer\Time;

final class DurationSpec extends ObjectBehavior
{
    private $duration = 123456.7891234;

    function let()
    {
        $this->beConstructedWith($this->duration);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Duration::class);
    }

    function it_returns_the_current_duration_as_microseconds()
    {
        $this->asMicroseconds()->shouldBeDouble();
        $this->asMicroseconds()->shouldBe($this->duration * 1000 * 1000);
    }

    function it_returns_the_current_time_as_milliseconds()
    {
        $this->asMilliseconds()->shouldBeDouble();
        $this->asMilliseconds()->shouldBe(
            $this->duration * 1000
        );
    }

    function it_is_initializable_through_times()
    {
        $start = Time::now();
        $end = Time::now();

        $this->beConstructedThrough('fromTimes', [$start, $end]);

        $this->asSeconds()->shouldBe($end->asSeconds() - $start->asSeconds());
    }
}
