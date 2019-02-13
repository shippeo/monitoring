<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Tag;

use Shippeo\Heimdall\Domain\Model\Identifier\UserId;

final class User implements Tag
{
    /** @var null|UserId */
    private $value;

    public function __construct(?UserId $value)
    {
        $this->value = $value;
    }

    public function name(): Name
    {
        return new Name('user');
    }

    public function value(): string
    {
        return (string) $this->value;
    }
}
