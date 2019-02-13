<?php

declare(strict_types=1);

namespace Fake;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;

final class Tag implements \Shippeo\Heimdall\Domain\Metric\Tag\Tag
{
    public function name(): Name
    {
        return new Name('fakeTagName');
    }

    public function value(): string
    {
        return 'fakeTagValue';
    }
}
