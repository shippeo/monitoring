<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Tag;

final class NullTag implements Tag
{
    /** @var Name */
    private $name;

    public function __construct(Name $name)
    {
        $this->name = $name;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function value(): string
    {
        return '';
    }
}
