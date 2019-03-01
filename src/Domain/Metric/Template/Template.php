<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Template;

use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;

interface Template
{
    public function name(): string;

    public function tags(): NameIterator;
}
