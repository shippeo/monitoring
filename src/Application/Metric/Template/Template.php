<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Metric\Template;

use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;

interface Template
{
    public function type(): string;

    public function name(): string;

    public function value(): int;

    public function tags(): TagCollection;
}
