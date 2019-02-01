<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Database\StatsD;

final class Key
{
    /** @var string */
    private $key;

    /** @var array */
    private $tags;

    public function __construct(string $key, array $tags)
    {
        $this->key = $key;
        $this->tags = $tags;
    }

    public function __toString(): string
    {
        $key = $this->key;
        foreach ($this->tags as $name => $value) {
            $key .= ",${name}=${value}";
        }

        return $key;
    }
}
