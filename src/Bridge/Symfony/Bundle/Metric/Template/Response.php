<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template;

use Shippeo\Heimdall\Domain\Metric\Tag;
use Shippeo\Heimdall\Domain\Metric\Template\Counter;

final class Response implements Counter
{
    public function name(): string
    {
        return 'api.response';
    }

    public function value(): int
    {
        return 1;
    }

    public function tags(): Tag\NameIterator
    {
        return new Tag\NameIterator(
            [
                new Tag\Name('endpoint'),
                new Tag\Name('status_code'),
                new Tag\Name('user'),
                new Tag\Name('organization'),
            ]
        );
    }
}
