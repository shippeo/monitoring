<?php

declare(strict_types=1);

namespace Functional;

use Fake\StandardUser;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Database\StatsD\Client;
use Shippeo\Heimdall\Application\Database\StatsD\Key;
use Shippeo\Heimdall\Application\Database\StatsD\StatsD;
use Shippeo\Heimdall\Application\Metric\Request;

/**
 * @internal
 * @coversNothing
 */
final class StatsDTest extends TestCase
{
    public function testSendRequestMetric(): void
    {
        $endpoint = 'fakeEndpoint';
        $user = new StandardUser();

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

        (
            new AddMetric([new StatsD($client->reveal())])
        )(
            new Request($user, $endpoint)
        );
    }
}
