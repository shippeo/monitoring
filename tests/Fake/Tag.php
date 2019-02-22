<?php

declare(strict_types=1);

namespace Fake;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;

final class Tag implements \Shippeo\Heimdall\Domain\Metric\Tag\Tag
{
    /** @var string */
    private $name;
    /** @var string */
    private $value;

    public function __construct(string $name = 'fakeTagName', string $value = 'fakeTagValue')
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function name(): Name
    {
        return new Name($this->name);
    }

    public function value(): string
    {
        return $this->value;
    }
}
