<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Tag;

use Webmozart\Assert\Assert;

/** @implements \IteratorAggregate<int, Tag> */
final class TagIterator implements \IteratorAggregate
{
    /** @var Tag[] */
    private $tags = [];

    /** @param iterable<int, Tag> $tags */
    public function __construct(iterable $tags)
    {
        Assert::allIsInstanceOf($tags, Tag::class);

        /** @var Tag $tag */
        foreach ($tags as $tag) {
            $this->tags[] = $tag;
        }
    }

    /** @return \Generator<int, Tag> */
    public function getIterator(): \Generator
    {
        yield from $this->tags;
    }
}
