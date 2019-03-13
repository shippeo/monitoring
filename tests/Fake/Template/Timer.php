<?php

declare(strict_types=1);

namespace Fake\Template;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;
use Shippeo\Heimdall\Domain\Metric\Timer\Duration;

final class Timer implements \Shippeo\Heimdall\Domain\Metric\Template\Timer
{
    /** @var Duration */
    private $duration;

    public function __construct()
    {
        $this->duration = new Duration(123456.789198765);
    }

    public function duration(): Duration
    {
        return $this->duration;
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
