<?php

declare(strict_types=1);

namespace  Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag;

use Fake\StandardUser;
use Fake\User;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag\TagCollectorInterface;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag\UserTagCollector;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider;
use Shippeo\Heimdall\Domain\Metric\Tag\Organization as OrganizationTag;
use Shippeo\Heimdall\Domain\Metric\Tag\User as UserTag;

class UserTagCollectorSpec extends ObjectBehavior
{
    function let(UserProvider $userProvider)
    {
        $this->beConstructedWith($userProvider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserTagCollector::class);
    }

    function it_implements_TagCollectorInterface()
    {
        $this->shouldImplement(TagCollectorInterface::class);
    }

    function it_returns_empty_tags_collection_when_no_user_is_connected(
        HTTPContext $httpContext,
        CliContext $cliContext,
        UserProvider $userProvider
    ) {
        $userProvider->connectedUser()->willReturn(null);
        $this->http($httpContext)->shouldBe([]);
        $this->cli($cliContext)->shouldBe([]);
    }

    function it_returns_user_tag(HTTPContext $httpContext, CliContext $cliContext, UserProvider $userProvider)
    {
        $user = new User();
        $userProvider->connectedUser()->willReturn($user);
        $tags = [new UserTag($user->id())];

        $this->http($httpContext)->shouldBeLike($tags);
        $this->cli($cliContext)->shouldBeLike($tags);
    }

    function it_returns_user_and_organization_tags_for_StandardUser(
        HTTPContext $httpContext,
        CliContext $cliContext,
        UserProvider $userProvider
    ) {
        $user = new StandardUser();
        $userProvider->connectedUser()->willReturn($user);
        $tags = [
            new UserTag($user->id()),
            new OrganizationTag($user->organization()->id()),
        ];

        $this->http($httpContext)->shouldBeLike($tags);
        $this->cli($cliContext)->shouldBeLike($tags);
    }
}
