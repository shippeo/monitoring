<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider;

use Shippeo\Heimdall\Domain\Model\User;

interface UserProvider
{
    public function connectedUser(): ?User;
}
