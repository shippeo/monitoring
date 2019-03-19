<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Timer;

final class Time
{
    /** @var float */
    private $time;

    private function __construct(float $time)
    {
        $this->time = $time;
    }

    public static function now(): self
    {
        return new self(\microtime(true));
    }

    public function asSeconds(): float
    {
        return $this->time;
    }

    public function asMilliseconds(): float
    {
        return $this->asSeconds() * 1000;
    }

    public function asMicroseconds(): float
    {
        return $this->asMilliseconds() * 1000;
    }
}
