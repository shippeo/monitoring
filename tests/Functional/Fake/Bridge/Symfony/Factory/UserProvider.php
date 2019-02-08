<?php

declare(strict_types=1);

namespace Functional\Fake\Bridge\Symfony\Factory;

use Shippeo\Heimdall\Domain\Model\User;

final class UserProvider implements \Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider
{
    /** @var null|User */
    private $connectedUser;

    public function connectedUser(): ?User
    {
        return $this->connectedUser;
    }

    public function setConnectedUser(?User $user): void
    {
        $this->connectedUser = $user;
    }
}
