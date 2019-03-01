<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Application\Metric\Tag\Endpoint;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Application\Metric\Template\Template;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode as Code;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Response;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Tag\Organization;
use Shippeo\Heimdall\Domain\Metric\Tag\User;

final class ResponseSpec extends ObjectBehavior
{
    /** @var Code */
    private $code;

    function let()
    {
        $this->code = new Code(123);

        $this->beConstructedWith($this->code, null, null);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Response::class);
    }

    function it_implements_Template()
    {
        $this->shouldImplement(Template::class);
    }

    function it_returns_the_counter_type()
    {
        $this->type()->shouldBe(Counter::class);
    }

    function it_returns_the_name()
    {
        $this->name()->shouldBe('api.response');
    }

    function it_returns_the_value()
    {
        $this->value()->shouldBe(1);
    }

    function it_returns_the_expected_tags()
    {
        $this
            ->tags()
            ->shouldBeLike(
                new TagCollection(
                    [
                        new Endpoint(''),
                        new StatusCode($this->code),
                        new User(null),
                        new Organization(null),
                    ]
                )
            )
        ;
    }

    function it_returns_the_expected_tags_with_an_endpoint()
    {
        $endpoint = 'fakeEndpoint';
        $this->beConstructedWith($this->code, null, $endpoint);

        $this
            ->tags()
            ->shouldBeLike(
                new TagCollection(
                    [
                        new Endpoint($endpoint),
                        new StatusCode($this->code),
                        new User(null),
                        new Organization(null),
                    ]
                )
            )
        ;
    }

    function it_returns_the_expected_tags_with_a_user()
    {
        $user = new \Fake\User();
        $this->beConstructedWith($this->code, $user, null);

        $this
            ->tags()
            ->shouldBeLike(
                new TagCollection(
                    [
                        new Endpoint(''),
                        new StatusCode($this->code),
                        new User($user->id()),
                        new Organization(null),
                    ]
                )
            )
        ;
    }

    function it_returns_the_expected_tags_with_a_standard_user()
    {
        $user = new \Fake\StandardUser();
        $this->beConstructedWith($this->code, $user, null);

        $this
            ->tags()
            ->shouldBeLike(
                new TagCollection(
                    [
                        new Endpoint(''),
                        new StatusCode($this->code),
                        new User($user->id()),
                        new Organization($user->organization()->id()),
                    ]
                )
            )
        ;
    }

    function it_returns_the_expected_tags_everything_filled()
    {
        $endpoint = 'fakeEndpoint';
        $user = new \Fake\StandardUser();
        $this->beConstructedWith($this->code, $user, $endpoint);

        $this
            ->tags()
            ->shouldBeLike(
                new TagCollection(
                    [
                        new Endpoint($endpoint),
                        new StatusCode($this->code),
                        new User($user->id()),
                        new Organization($user->organization()->id()),
                    ]
                )
            )
        ;
    }
}
