<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric;

use Shippeo\Heimdall\Domain\User;

final class Request extends Increment
{
    /** @var User */
    private $user;

    /** @var null|string */
    private $endpoint;

    public function __construct(User $user, ?string $endpoint)
    {
        $this->user = $user;
        $this->endpoint = $endpoint;
    }

    public function key(): string
    {
        return 'api.request';
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
