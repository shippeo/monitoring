<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Metric\Tag;

use Shippeo\Heimdall\Application\Util\TypedCollection;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;
use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;

/**
 * @extends TypedCollection<Tag>
 */
final class TagCollection extends TypedCollection
{
    /** @param Tag[] $tags */
    public function __construct(array $tags)
    {
        parent::__construct(Tag::class, $tags);
    }

    public function mergeWith(self $collection): self
    {
        return new self(
            \array_merge(
                $this->toArray(),
                $collection->toArray()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): TagIterator
    {
        return new TagIterator($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): Tag
    {
        return parent::offsetGet($offset);
    }
}
