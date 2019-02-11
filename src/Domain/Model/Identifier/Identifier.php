<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Model\Identifier;

use Webmozart\Assert\Assert;

abstract class Identifier
{
    private const HASHID_PATTERN = '/^[A-Z0-9]{8}$/';

    /** @var string */
    private $id;

    public function __construct(string $id)
    {
        Assert::regex($id, self::HASHID_PATTERN);

        $this->id = $id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
