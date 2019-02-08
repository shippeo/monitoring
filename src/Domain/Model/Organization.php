<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Model;

use Shippeo\Heimdall\Domain\Model\Identifier\OrganizationId;

interface Organization
{
    public function id(): OrganizationId;
}
