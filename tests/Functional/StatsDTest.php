<?php

declare(strict_types=1);

namespace Functional;

use Fake\StandardUser;
use Fake\Tag as FakeTag;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Database\StatsD\Client;
use Shippeo\Heimdall\Application\Database\StatsD\Key;
use Shippeo\Heimdall\Application\Database\StatsD\StatsD;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP\StatusCode as StatusCodeTag;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP\DatabaseTime;
use Shippeo\Heimdall\Domain\Metric\Tag;

/**
 * @internal
 * @coversNothing
 */
final class StatsDTest extends TestCase
{
    use ProphecyTrait;

    public function testSendDatabaseTimeMetric(): void
    {
        $endpoint = 'fakeEndpoint';
        $user = new StandardUser();
        $globalTag = new FakeTag();
        $tags = new TagCollection(
            [
                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint($endpoint),
                new StatusCodeTag(new StatusCode(200)),
                new Tag\User($user->id()),
                new Tag\Organization($user->organization()->id()),
            ]
        );

        $client = $this->prophesize(Client::class);
        /** @var Tag\TagIterator $allTags */
        $allTags = $tags->mergeWith(new TagCollection([$globalTag]))->getIterator();
        $client
            ->gauge(
                Argument::exact(
                    new Key(
                        'http.database',
                        $allTags
                    )
                ),
                Argument::type('float')
            )
            ->shouldBeCalled()
        ;

        (
            new AddMetric([new StatsD($client->reveal())], new TagCollection([$globalTag]))
        )(
            new DatabaseTime(12.3456),
            $tags
        );
    }
}
