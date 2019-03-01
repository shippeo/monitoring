<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric\Timer;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Timer\Time;

final class TimeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('now');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Time::class);
    }

    function it_returns_the_current_time_as_microseconds()
    {
        $this->asMicroseconds()->shouldBeDouble();
    }

    function it_returns_the_current_time_as_milliseconds()
    {
        $this->asMilliseconds()->shouldBeDouble();
        $this->asMilliseconds()->shouldBe(
            $this->asMicroseconds()->getWrappedObject() * 1000
        );
    }
}
