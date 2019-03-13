<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric;

use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;
use Shippeo\Heimdall\Domain\Metric\Timer\Duration;

final class Timer implements Metric
{
    /** @var string */
    private $key;
    /** @var Duration */
    private $duration;
    /** @var TagIterator */
    private $tags;

    public function __construct(string $key, Duration $duration, TagIterator $tags)
    {
        $this->key = $key;
        $this->duration = $duration;
        $this->tags = $tags;
    }

    /**
     * {@inheritdoc}
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     *
     * Return the number of milliseconds
     */
    public function value(): float
    {
        return $this->duration->asMilliseconds();
    }

    /**
     * {@inheritdoc}
     */
    public function tags(): TagIterator
    {
        return $this->tags;
    }
}
