<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric;

use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;
use Shippeo\Heimdall\Domain\Metric\Timer\Time;

final class Timer implements Metric
{
    /** @var string */
    private $key;
    /** @var Time */
    private $start;
    /** @var Time */
    private $end;
    /** @var TagIterator */
    private $tags;

    public function __construct(string $key, Time $start, Time $end, TagIterator $tags)
    {
        $this->key = $key;
        $this->start = $start;
        $this->end = $end;
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
        return \round($this->end->asMilliseconds() - $this->start->asMilliseconds(), 4);
    }

    /**
     * {@inheritdoc}
     */
    public function tags(): TagIterator
    {
        return $this->tags;
    }

}
