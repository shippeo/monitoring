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
        $key = $this->normalize($this->key);

        foreach ($this->tags as $tag) {
            $key .= ','.$this->normalize((string) $tag->name()).'='.$this->normalize($tag->value());
        }

        return $key;
    }

    /**
     * @see http://opentsdb.net/docs/build/html/user_guide/writing/index.html#metrics-and-tags
     */
    private function normalize(string $value): string
    {
        return \Safe\preg_replace('#[^a-zA-Z0-9\/\.\-_]#', '_', $value);
    }
}
