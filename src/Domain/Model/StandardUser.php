<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Model;

interface StandardUser extends User
{
    public function organization(): Organization;
}
