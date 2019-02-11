<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Application\Metric;

use Fake\StandardUser;
use Fake\User;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Application\Metric\Request;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Metric;

class RequestSpec extends ObjectBehavior
{
    /** @var string */
    private $endpoint = 'fakeEndpoint';

    function let()
    {
        $this->beConstructedWith(new User(), $this->endpoint);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Request::class);
    }

    function it_extends_Increment()
    {
        $this->shouldHaveType(Counter::class);
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
                    'endpoint' => $this->endpoint,
                    'organization' => null,
                    'user' => (string) $user->id(),
                ]
            )
        ;
    }

    function it_returns_the_tags_for_a_standard_user()
    {
        $user = new StandardUser();

        $this->beConstructedWith($user, $this->endpoint);

        $this
            ->tags()
            ->shouldBe(
                [
                    'endpoint' => $this->endpoint,
                    'organization' => (string) $user->organization()->id(),
                    'user' => (string) $user->id(),
                ]
            )
        ;
    }

    function it_returns_the_tags_without_user()
    {
        $this->beConstructedWith(null, $this->endpoint);

        $this
            ->tags()
            ->shouldBe(
                [
                    'endpoint' => $this->endpoint,
                    'organization' => null,
                    'user' => null,
                ]
            )
        ;
    }
}
