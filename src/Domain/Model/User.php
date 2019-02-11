<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Model;

use Shippeo\Heimdall\Domain\Model\Identifier\UserId;

interface User
{
    public function id(): UserId;
}
