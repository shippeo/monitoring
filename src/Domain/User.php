<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain;

interface User
{
    public function id(): string;

    public function organization(): Organization;
}
