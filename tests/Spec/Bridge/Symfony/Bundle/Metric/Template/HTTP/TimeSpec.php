<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP\Time;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;
use Shippeo\Heimdall\Domain\Metric\Template\Template;
use Shippeo\Heimdall\Domain\Metric\Template\Timer;
use Shippeo\Heimdall\Domain\Metric\Timer\Duration;
use Shippeo\Heimdall\Domain\Metric\Timer\Time as MetricTime;

final class TimeSpec extends ObjectBehavior
{
    /** @var MetricTime */
    private $start;
    /** @var MetricTime */
    private $end;

    function let()
    {
        $this->start = MetricTime::now();
        $this->end = MetricTime::now();

        $this->beConstructedWith($this->start, $this->end);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Time::class);
    }

    function it_implements_Timer()
    {
        $this->shouldImplement(Timer::class);
    }

    function it_implements_Template()
    {
        $this->shouldImplement(Template::class);
    }

    function it_returns_the_name()
    {
        $this->name()->shouldBe('http.time');
    }

    function it_returns_the_duration()
    {
        $this
            ->duration()
            ->shouldBeLike(Duration::fromTimes($this->start, $this->end))
        ;
    }

    function it_returns_the_expected_tags()
    {
        $this
            ->tags()
            ->shouldBeLike(
                new NameIterator(
                    [
                        new Name('endpoint'),
                        new Name('status_code'),
                        new Name('user'),
                        new Name('organization'),
                    ]
                )
            )
        ;
    }
}
