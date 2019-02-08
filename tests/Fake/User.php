<?php

declare(strict_types=1);

namespace Fake;

use Shippeo\Heimdall\Domain\Model\Identifier\UserId;

final class User implements \Shippeo\Heimdall\Domain\Model\User
{
    /** @var UserId */
    private $id;

    public function __construct()
    {
        $this->id = new UserId('POI64MLK');
    }

    public function id(): UserId
    {
        return $this->id;
    }
}
