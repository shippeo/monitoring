<?php

declare(strict_types=1);

namespace Fake\Template;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;

final class Counter implements \Shippeo\Heimdall\Domain\Metric\Template\Counter
{
    public function value(): int
    {
        return 123;
    }

    public function name(): string
    {
        return 'fakeCounterName';
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
