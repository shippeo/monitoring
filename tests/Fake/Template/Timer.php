<?php

declare(strict_types=1);

namespace Fake\Template;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;
use Shippeo\Heimdall\Domain\Metric\Timer\Time;

final class Timer implements \Shippeo\Heimdall\Domain\Metric\Template\Timer
{
    /** @var Time */
    private $start;
    /** @var Time */
    private $end;

    public function __construct()
    {
        $this->start = Time::now();
        $this->end = Time::now();
    }

    public function start(): Time
    {
        return $this->start;
    }

    public function end(): Time
    {
        return $this->end;
    }

    public function name(): string
    {
        return 'fakeTimerName';
    }

    public function tags(): NameIterator
    {
        return new NameIterator(
            [
                new Name('fakeName1'),
                new Name('fakeName2'),
            ]
        );
    }
}
