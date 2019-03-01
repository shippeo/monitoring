<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Tag;

use Webmozart\Assert\Assert;

final class Name
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $name = \trim($name);
        Assert::notEmpty($name);
        Assert::notRegex($name, '/.*\s.*/');

        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function equalTo(self $otherName): bool
    {
        return (string) $this === (string) $otherName;
    }
}
