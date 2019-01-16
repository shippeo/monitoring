<?php

declare(strict_types=1);

namespace Functional;

use Fake\User;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Shippeo\Heimdall\Application\Database\StatsD\Client;
use Shippeo\Heimdall\Application\Database\StatsD\Key;
use Shippeo\Heimdall\Application\Database\StatsD\StatsD;
use Shippeo\Heimdall\Domain\AddMetric;
use Shippeo\Heimdall\Domain\Database\DatabaseIterator;
use Shippeo\Heimdall\Domain\Metric\Request;

/**
 * @internal
 * @coversNothing
 */
final class StatsDTest extends TestCase
{
    public function testSendRequestMetric(): void
    {
        $endpoint = 'fakeEndpoint';
        $user = new User();

        $client = $this->prophesize(Client::class);
        $client
            ->increment(
                Argument::exact(
                    new Key(
                        'api.request',
                        [
                            'endpoint' => $endpoint,
                            'organization' => $user->organization()->id(),
                            'user' => $user->id(),
                        ]
                    )
                ),
                1
            )
            ->shouldBeCalled()
        ;

        (new AddMetric(new DatabaseIterator([new StatsD($client->reveal())])))(
            new Request($user, $endpoint)
        );
    }
}
