<?php

declare(strict_types=1);

namespace Functional;

use Fake\StandardUser;
use Fake\Tag as FakeTag;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Database\StatsD\Client;
use Shippeo\Heimdall\Application\Database\StatsD\Key;
use Shippeo\Heimdall\Application\Database\StatsD\StatsD;
use Shippeo\Heimdall\Application\Metric\Factory;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Application\Metric\Template\Request;
use Shippeo\Heimdall\Domain\Metric\Tag;

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
        $globalTag = new FakeTag();

        $client = $this->prophesize(Client::class);
        $client
            ->increment(
                Argument::exact(
                    new Key(
                        'api.request',
                        new Tag\TagIterator(
                            [
                                new \Shippeo\Heimdall\Application\Metric\Tag\Endpoint($endpoint),
                                new Tag\Organization($user->organization()->id()),
                                new Tag\User($user->id()),
                                $globalTag,
                            ]
                        )
                    )
                ),
                1
            )
            ->shouldBeCalled()
        ;

        (
            new AddMetric([new StatsD($client->reveal())], new Factory(new TagCollection([$globalTag])))
        )(
            new Request($user, $endpoint)
        );
    }
}
