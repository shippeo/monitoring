<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric\Tag;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;
use Shippeo\Heimdall\Domain\Metric\Tag\User;
use Shippeo\Heimdall\Domain\Model\Identifier\UserId;

final class UserSpec extends ObjectBehavior
{
    /** @var UserId */
    private $userId;

    function let()
    {
        $user = new \Fake\User();
        $this->userId = $user->id();

        $this->beConstructedWith($this->userId);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    function it_implements_Tag()
    {
        $this->shouldImplement(Tag::class);
    }

    function it_returns_the_expected_name()
    {
        $this->name()->shouldBeLike(new Name('user'));
    }

    function it_returns_the_expected_value()
    {
        $this->value()->shouldBe((string) $this->userId);
    }

    function it_returns_an_empty_value_when_initialized_with_null()
    {
        $this->beConstructedWith(null);

        $this->value()->shouldBe('');
    }
}
