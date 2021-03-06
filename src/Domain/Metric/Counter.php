<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric;

use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;

final class Counter implements Metric
{
    /** @var string */
    private $key;
    /** @var int */
    private $value;
    /** @var TagIterator */
    private $tags;

    public function __construct(string $key, int $value, TagIterator $tags)
    {
        $this->key = $key;
        $this->value = $value;
        $this->tags = $tags;
    }

    public function key(): string
    {
        return $this->key;
    }

    /**
     * Returns a positive int to increment and a negative int to decrement.
     */
    public function value(): int
    {
        return $this->value;
    }

    public function tags(): TagIterator
    {
        return $this->tags;
    }
}
