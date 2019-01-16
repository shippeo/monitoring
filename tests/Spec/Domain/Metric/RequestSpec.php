<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric;

use Fake\User;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Increment;
use Shippeo\Heimdall\Domain\Metric\Metric;
use Shippeo\Heimdall\Domain\Metric\Request;

class RequestSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new User(), null);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Request::class);
    }

    function it_extends_Increment()
    {
        $this->shouldHaveType(Increment::class);
    }

    function it_implements_Metric()
    {
        $this->shouldImplement(Metric::class);
    }

    function it_returns_the_key()
    {
        $this->key()->shouldBe('api.request');
    }

    function it_returns_the_increment_value()
    {
        $this->value()->shouldBe(1);
    }

    function it_returns_the_tags()
    {
        $user = new User();

        $this
            ->tags()
            ->shouldBe(
                [
                    'endpoint' => null,
                    'organization' => $user->organization()->id(),
                    'user' => $user->id(),
                ]
            )
        ;
    }
}
