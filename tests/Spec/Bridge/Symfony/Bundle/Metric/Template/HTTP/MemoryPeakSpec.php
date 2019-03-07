<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP\MemoryPeak;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;
use Shippeo\Heimdall\Domain\Metric\Template\Gauge;
use Shippeo\Heimdall\Domain\Metric\Template\Template;

final class MemoryPeakSpec extends ObjectBehavior
{
    /** @var int */
    private $value = 123456;

    function let()
    {
        $this->beConstructedWith($this->value);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MemoryPeak::class);
    }

    function it_implements_Counter()
    {
        $this->shouldImplement(Gauge::class);
    }

    function it_implements_Template()
    {
        $this->shouldImplement(Template::class);
    }

    function it_returns_the_name()
    {
        $this->name()->shouldBe('api.memory_peak');
    }

    function it_returns_the_value()
    {
        $this->value()->shouldBe($this->value);
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
