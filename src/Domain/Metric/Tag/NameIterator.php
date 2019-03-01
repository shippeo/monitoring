<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Tag;

use Webmozart\Assert\Assert;

final class NameIterator implements \Iterator
{
    /** @var Name[] */
    private $names = [];

    public function __construct(iterable $names)
    {
        Assert::allIsInstanceOf($names, Name::class);

        foreach ($names as $name) {
            $this->names[] = $name;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function current(): Name
    {
        return \current($this->names);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        \next($this->names);
    }

    /**
     * {@inheritdoc}
     *
     * @return null|int|string
     */
    public function key()
    {
        return \key($this->names);
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return \current($this->names) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        \reset($this->names);
    }
}
