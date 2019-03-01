<?php

declare(strict_types=1);

namespace Fake\Tag;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;

final class Tag1 implements Tag
{
    public function name(): Name
    {
        return new Name('fakeName1');
    }

    public function value(): string
    {
        return 'fakeValue1';
    }
}
