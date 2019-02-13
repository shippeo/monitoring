<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Application\Metric\Template;

use Fake\StandardUser;
use Fake\User;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Application\Metric\Tag\Endpoint;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Application\Metric\Template\Request;
use Shippeo\Heimdall\Application\Metric\Template\Template;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Tag;

final class RequestSpec extends ObjectBehavior
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

    function it_implements_Template()
    {
        $this->shouldImplement(Template::class);
    }

    function it_returns_the_type()
    {
        $this->type()->shouldBe(Counter::class);
    }

    function it_returns_the_name()
    {
        $this->name()->shouldBe('api.request');
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
            ->shouldBeLike(
                new TagCollection(
                    [
                        new Endpoint($this->endpoint),
                        new Tag\Organization(null),
                        new Tag\User($user->id()),
                    ]
                )
            )
        ;
    }

    function it_returns_the_tags_for_a_standard_user()
    {
        $user = new StandardUser();

        $this->beConstructedWith($user, $this->endpoint);

        $this
            ->tags()
            ->shouldBeLike(
                new TagCollection(
                    [
                        new Endpoint($this->endpoint),
                        new Tag\Organization($user->organization()->id()),
                        new Tag\User($user->id()),
                    ]
                )
            )
        ;
    }

    function it_returns_the_tags_without_user()
    {
        $this->beConstructedWith(null, $this->endpoint);

        $this
            ->tags()
            ->shouldBeLike(
                new TagCollection(
                    [
                        new Endpoint($this->endpoint),
                        new Tag\Organization(null),
                        new Tag\User(null),
                    ]
                )
            )
        ;
    }
}
