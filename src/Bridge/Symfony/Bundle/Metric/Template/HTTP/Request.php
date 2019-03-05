<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;
use Shippeo\Heimdall\Domain\Metric\Template\Counter;

final class Request implements Counter
{
    public function value(): int
    {
        return 1;
    }

    public function name(): string
    {
        return 'api.request';
    }

    public function tags(): NameIterator
    {
        return new NameIterator(
            [
                new Name('endpoint'),
                new Name('organization'),
                new Name('user'),
            ]
        );
    }
}
