<?php

declare(strict_types=1);

namespace Fake;

final class Organization implements \Shippeo\Heimdall\Domain\Organization
{
    public function id(): string
    {
        return 'fakeOrganizationId';
    }
}
