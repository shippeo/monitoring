<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Database\StatsD;

use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;

final class Key
{
    /** @var string */
    private $key;

    /** @var TagIterator */
    private $tags;

    public function __construct(string $key, TagIterator $tags)
    {
        $this->key = $key;
        $this->tags = $tags;
    }

    public function __toString(): string
    {
        $key = $this->key;
        foreach ($this->tags as $tag) {
            $key .= ','.$tag->name().'='.$tag->value();
        }

        return $key;
    }
}
