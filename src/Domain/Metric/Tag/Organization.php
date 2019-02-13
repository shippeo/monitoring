<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Tag;

use Shippeo\Heimdall\Domain\Model\Identifier\OrganizationId;

final class Organization implements Tag
{
    /** @var null|OrganizationId */
    private $value;

    public function __construct(?OrganizationId $value)
    {
        $this->value = $value;
    }

    public function name(): Name
    {
        return new Name('organization');
    }

    public function value(): string
    {
        return (string) $this->value;
    }
}
