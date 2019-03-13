<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP\DatabaseTime;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;
use Shippeo\Heimdall\Domain\Metric\Template\Template;
use Shippeo\Heimdall\Domain\Metric\Template\Timer;
use Shippeo\Heimdall\Domain\Metric\Timer\Duration;

final class DatabaseTimeSpec extends ObjectBehavior
{
    /** @var float */
    private $duration = 1234.56789;

    function let()
    {
        $this->beConstructedWith($this->duration);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DatabaseTime::class);
    }

    function it_implements_Counter()
    {
        $this->shouldImplement(Timer::class);
    }

    function it_implements_Template()
    {
        $this->shouldImplement(Template::class);
    }

    function it_returns_the_name()
    {
        $this->name()->shouldBe('api.database');
    }

    function it_returns_the_duration()
    {
        $this
            ->duration()
            ->shouldBeLike(new Duration($this->duration))
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
