<?php

declare(strict_types=1);

namespace Fake\Template;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;

final class Gauge implements \Shippeo\Heimdall\Domain\Metric\Template\Gauge
{
    public function value(): float
    {
        return 123.45;
    }

    public function name(): string
    {
        return 'fakeGaugeName';
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
