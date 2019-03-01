<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode as Code;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;

final class StatusCode implements Tag
{
    /** @var Code */
    private $code;

    public function __construct(Code $code)
    {
        $this->code = $code;
    }

    public function name(): Name
    {
        return new Name('status_code');
    }

    public function value(): string
    {
        return (string) $this->code->value();
    }
}
