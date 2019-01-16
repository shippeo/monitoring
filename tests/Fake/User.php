<?php

declare(strict_types=1);

namespace Fake;

use Fake\Organization as FakeOrganization;
use Shippeo\Heimdall\Domain\Organization;

final class User implements \Shippeo\Heimdall\Domain\User
{
    /** @var Organization */
    private $organization;

    public function __construct()
    {
        $this->organization = new FakeOrganization();
    }

    public function id(): string
    {
        return 'fakeUserId';
    }

    public function organization(): Organization
    {
        return $this->organization;
    }
}
