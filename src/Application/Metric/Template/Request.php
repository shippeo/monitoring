<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Metric\Template;

use Shippeo\Heimdall\Application\Metric\Tag\Endpoint;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Tag;
use Shippeo\Heimdall\Domain\Model\StandardUser;
use Shippeo\Heimdall\Domain\Model\User;

final class Request implements Template
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

    public function type(): string
    {
        return Counter::class;
    }

    public function name(): string
    {
        return 'api.request';
    }

    public function value(): int
    {
        return 1;
    }

    public function tags(): TagCollection
    {
        return new TagCollection(
            [
                new Endpoint($this->endpoint),
                new Tag\Organization(
                    ($this->user instanceof StandardUser) ? $this->user->organization()->id() : null
                ),
                new Tag\User($this->user !== null ? $this->user->id() : null),
            ]
        );
    }
}
