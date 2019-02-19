<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Metric\Tag;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;

final class Endpoint implements Tag
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function name(): Name
    {
        return new Name('endpoint');
    }

    public function value(): string
    {
        return $this->value;
    }
}
