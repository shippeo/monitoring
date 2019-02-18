<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;

final class GlobalTag implements Tag
{
    /** @var string */
    private $name;
    /** @var string */
    private $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function name(): Name
    {
        return new Name($this->name);
    }

    public function value(): string
    {
        return $this->value;
    }
}
