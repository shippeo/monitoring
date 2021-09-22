<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Database;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Database\Database;
use Shippeo\Heimdall\Domain\Database\DatabaseIterator;

class DatabaseIteratorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DatabaseIterator::class);
    }

    function it_implements_IteratorAggregate()
    {
        $this->shouldImplement(\IteratorAggregate::class);
    }

    function it_throws_an_InvalidArgumentException_if_an_invalid_instance_is_passed_as_argument()
    {
        $this->beConstructedWith([new \stdClass()]);

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_iterates_correctly(Database $database1, Database $database2)
    {
        $this->beConstructedWith([$database1, $database2]);

        $this->shouldIterateAs([$database1, $database2]);
    }
}
