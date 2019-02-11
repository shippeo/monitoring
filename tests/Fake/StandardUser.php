<?php

declare(strict_types=1);

namespace Fake;

use Fake\Organization as FakeOrganization;
use Shippeo\Heimdall\Domain\Model\Identifier\UserId;
use Shippeo\Heimdall\Domain\Model\Organization;

final class StandardUser implements \Shippeo\Heimdall\Domain\Model\StandardUser
{
    /** @var UserId */
    private $id;
    /** @var Organization */
    private $organization;

    public function __construct()
    {
        $this->id = new UserId('ABC78XYZ');
        $this->organization = new FakeOrganization();
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function organization(): Organization
    {
        return $this->organization;
    }
}
