<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Tag;

interface Tag
{
    public function name(): Name;

    public function value(): string;
}
