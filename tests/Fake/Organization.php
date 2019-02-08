<?php

declare(strict_types=1);

namespace Fake;

use Shippeo\Heimdall\Domain\Model\Identifier\OrganizationId;

final class Organization implements \Shippeo\Heimdall\Domain\Model\Organization
{
    /** @var OrganizationId */
    private $id;

    public function __construct()
    {
        $this->id = new OrganizationId('ZYX87CBA');
    }

    public function id(): OrganizationId
    {
        return $this->id;
    }
}
