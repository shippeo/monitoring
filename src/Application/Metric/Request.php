<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Metric;

use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\User;

final class Request implements Counter
{
    /** @var User */
    private $user;

    /** @var null|string */
    private $endpoint;

    public function __construct(User $user, string $endpoint)
    {
        $this->user = $user;
        $this->endpoint = $endpoint;
    }

    public function key(): string
    {
        return 'api.request';
    }

    public function value(): int
    {
        return 1;
    }

    public function tags(): array
    {
        return [
            'endpoint' => $this->endpoint,
            'organization' => $this->user->organization()->id(),
            'user' => $this->user->id(),
        ];
    }
}
