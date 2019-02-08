<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Metric;

use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Model\StandardUser;
use Shippeo\Heimdall\Domain\Model\User;

final class Request implements Counter
{
    /** @var null|\Shippeo\Heimdall\Domain\Model\User */
    private $user;

    /** @var string */
    private $endpoint;

    public function __construct(?User $user, string $endpoint)
    {
        $this->user = $user;
        $this->endpoint = $endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function key(): string
    {
        return 'api.request';
    }

    /**
     * {@inheritdoc}
     */
    public function value(): int
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public function tags(): array
    {
        return $this->withUserTags(
            [
                'endpoint' => $this->endpoint,
                'organization' => null,
                'user' => null,
            ]
        );
    }

    private function withUserTags(array $tags): array
    {
        if ($this->user === null) {
            return $tags;
        }

        $tags['user'] = (string) $this->user->id();
        if ($this->user instanceof StandardUser) {
            $tags['organization'] = (string) $this->user->organization()->id();
        }

        return $tags;
    }
}
