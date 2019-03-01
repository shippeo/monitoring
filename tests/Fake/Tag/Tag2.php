<?php

declare(strict_types=1);

namespace Fake\Tag;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;

final class Tag2 implements Tag
{
    public function name(): Name
    {
        return new Name('fakeName2');
    }

    public function value(): string
    {
        return 'fakeValue2';
    }
}
