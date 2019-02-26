<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Model\Identifier;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Model\Identifier\Identifier;
use Shippeo\Heimdall\Domain\Model\Identifier\UserId;

final class UserIdSpec extends ObjectBehavior
{
    private $id = 'AZE12WXC';

    function let()
    {
        $this->beConstructedWith($this->id);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserId::class);
    }

    function it_extends_Identifier()
    {
        $this->shouldHaveType(Identifier::class);
    }

    function it_returns_the_given_id()
    {
        $this->__toString()->shouldReturn($this->id);
    }

    function it_throws_an_exception_if_value_is_too_short()
    {
        $this->beConstructedWith('1234567');
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_throws_an_exception_if_value_is_too_long()
    {
        $this->beConstructedWith('123456789');
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_throws_an_exception_if_value_contains_non_alpha_numerical_characters()
    {
        $this->beConstructedWith('1234567,');
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
