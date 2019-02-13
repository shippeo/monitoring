<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Tag;

use Webmozart\Assert\Assert;

final class TagIterator implements \Iterator
{
    /** @var Tag[] */
    private $tags = [];

    public function __construct(iterable $tags)
    {
        Assert::allIsInstanceOf($tags, Tag::class);

        /** @var Tag $tag */
        foreach ($tags as $tag) {
            $this->tags[] = $tag;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function current(): Tag
    {
        return \current($this->tags);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        \next($this->tags);
    }

    /**
     * {@inheritdoc}
     *
     * @return null|int|string
     */
    public function key()
    {
        return \key($this->tags);
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return \current($this->tags) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        \reset($this->tags);
    }
}
