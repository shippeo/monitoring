<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider;
use Shippeo\Heimdall\Domain\Metric\Tag\Organization;
use Shippeo\Heimdall\Domain\Metric\Tag\User;
use Shippeo\Heimdall\Domain\Model\StandardUser;

class UserTagCollector implements TagCollectorInterface
{
    /** @var UserProvider */
    private $userProvider;

    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function http(HTTPContext $context): array
    {
        return $this->collect();
    }

    public function cli(CliContext $context): array
    {
        return $this->collect();
    }

    private function collect(): array
    {
        $user = $this->userProvider->connectedUser();

        if ($user === null) {
            return [];
        }

        $tags = [
            new User($user->id()),
        ];

        if ($user instanceof StandardUser) {
            $tags[] = new Organization($user->organization()->id());
        }

        return $tags;
    }
}
