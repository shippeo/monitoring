<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric\Tag;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;

final class NameSpec extends ObjectBehavior
{
    private $value = 'fakeName';

    function let()
    {
        $this->beConstructedWith($this->value);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Name::class);
    }

    function it_returns_the_given_string()
    {
        $this->__toString()->shouldReturn($this->value);
    }

    function it_trims_the_given_string()
    {
        $value = ' fakeNameWithSpaces ';

        $this->beConstructedWith($value);

        $this->__toString()->shouldReturn(\trim($value));
    }

    function it_throws_an_exception_for_an_empty_string()
    {
        $this->beConstructedWith('');
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();

        $this->beConstructedWith(' ');
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_throws_an_exception_when_spaces_are_found_between_characters()
    {
        $this->beConstructedWith('a b');

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_returns_true_when_equals()
    {
        $value = 'fakeValue';
        $this->beConstructedWith($value);

        $this->equalTo(new Name($value))->shouldBe(true);
    }

    function it_returns_false_when_not_equals()
    {
        $this->beConstructedWith('fakeValue');

        $this->equalTo(new Name('notTheSameValue'))->shouldBe(false);
    }
}
