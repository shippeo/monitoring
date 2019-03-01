<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP;

use Webmozart\Assert\Assert;

final class StatusCode
{
    /** @var int */
    private $code;

    public function __construct(int $code)
    {
        Assert::range($code, 100, 599);

        $this->code = $code;
    }

    public function value(): int
    {
        return $this->code;
    }
}
