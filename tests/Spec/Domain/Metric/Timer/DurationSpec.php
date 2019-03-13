<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric\Timer;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Timer\Duration;

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
}
