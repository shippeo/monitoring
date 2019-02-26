<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric\Timer;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Timer\Time;

final class TimeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedThrough('now');

        $this->shouldHaveType(Time::class);
    }
}
