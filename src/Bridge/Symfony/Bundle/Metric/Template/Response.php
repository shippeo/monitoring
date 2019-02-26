<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template;

use Shippeo\Heimdall\Application\Metric\Tag\Endpoint;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Application\Metric\Template\Template;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP as HTTPTag;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Tag;
use Shippeo\Heimdall\Domain\Model\StandardUser;
use Shippeo\Heimdall\Domain\Model\User;

final class Response implements Template
{
    /** @var StatusCode */
    private $statusCode;
    /** @var null|User */
    private $user;
    /** @var null|string */
    private $endpoint;

    public function __construct(StatusCode $statusCode, ?User $user, ?string $endpoint)
    {
        $this->statusCode = $statusCode;
        $this->user = $user;
        $this->endpoint = $endpoint;
    }

    public function type(): string
    {
        return Counter::class;
    }

    public function name(): string
    {
        return 'api.response';
    }

    public function value(): int
    {
        return 1;
    }

    public function tags(): TagCollection
    {
        return new TagCollection(
            [
                new Endpoint((string) $this->endpoint),
                new HTTPTag\StatusCode($this->statusCode),
                new Tag\User(($this->user !== null) ? $this->user->id() : null),
                new Tag\Organization(
                    ($this->user instanceof StandardUser) ? $this->user->organization()->id() : null
                ),
            ]
        );
    }
}
