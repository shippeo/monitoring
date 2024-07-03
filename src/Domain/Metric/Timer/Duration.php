<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Timer;

use Webmozart\Assert\Assert;

final class Duration
{
    /** @var float */
    private $duration;

    /**
     * @param float $duration a duration in seconds
     */
    public function __construct(float $duration)
    {
        try {
            Assert::greaterThan($duration, 0, 'A duration must be positive or equal to 0.');
        } catch (\InvalidArgumentException $e) {
            $duration = 0;
        }

        $this->duration = $duration;
    }

    public static function fromTimes(Time $start, Time $end): self
    {
        return new self($end->asSeconds() - $start->asSeconds());
    }

    public function asSeconds(): float
    {
        return $this->duration;
    }

    public function asMilliseconds(): float
    {
        return $this->duration * 1000;
    }

    public function asMicroseconds(): float
    {
        return $this->asMilliseconds() * 1000;
    }
}
