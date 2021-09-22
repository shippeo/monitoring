<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Tag;

use Webmozart\Assert\Assert;

/** @implements \IteratorAggregate<int, Name> */
final class NameIterator implements \IteratorAggregate
{
    /** @var Name[] */
    private $names = [];

    /** @param iterable<int|Name> $names */
    public function __construct(iterable $names)
    {
        Assert::allIsInstanceOf($names, Name::class);

        foreach ($names as $name) {
            $this->names[] = $name;
        }
    }

    /**
     * @return \Generator<int, Name>
     */
    public function getIterator(): \Generator
    {
        yield from $this->names;
    }
}
