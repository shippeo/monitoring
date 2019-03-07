<?php

declare(strict_types=1);

namespace Functional\Bridge\Symfony;

use Functional\Fake\Bridge\Symfony\Provider\UserProvider as FakeUserProvider;
use Prophecy\Argument;
use Shippeo\Heimdall\Application\Database\StatsD\Client;
use Shippeo\Heimdall\Application\Database\StatsD\Key;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode as Code;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\GlobalTag;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider;
use Shippeo\Heimdall\Domain\Metric\Tag;
use Shippeo\Heimdall\Infrastructure\Database\StatsDClient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
final class HTTPTest extends WebTestCase
{
    /** @var \Symfony\Bundle\FrameworkBundle\Client */
    private $client;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testRequestOnIndexWithoutUser(): void
    {
        $statsDClient = $this->prophesize(Client::class);

        $this->clientContainer()->set(StatsDClient::class, $statsDClient->reveal());

        $statsDClient
            ->increment(
                Argument::exact(
                    new Key(
                        'api.request',
                        new Tag\TagIterator(
                            [
                                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                                new Tag\NullTag(new Tag\Name('organization')),
                                new Tag\NullTag(new Tag\Name('user')),
                                new GlobalTag('globalTag1', 'globalTagValue1'),
                                new GlobalTag('globalTag2', '2'),
                            ]
                        )
                    )
                ),
                1
            )
            ->shouldBeCalled()
        ;
        $endTags = new Tag\TagIterator(
            [
                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                new StatusCode(new Code(Response::HTTP_OK)),
                new Tag\NullTag(new Tag\Name('user')),
                new Tag\NullTag(new Tag\Name('organization')),
                new GlobalTag('globalTag1', 'globalTagValue1'),
                new GlobalTag('globalTag2', '2'),
            ]
        );
        $statsDClient
            ->increment(
                Argument::exact(new Key('api.response', $endTags)),
                1
            )
            ->shouldBeCalled()
        ;
        $statsDClient
            ->timing(
                Argument::exact(new Key('api.time', $endTags)),
                Argument::type('float')
            )
            ->shouldBeCalled()
        ;
        $statsDClient
            ->gauge(
                Argument::exact(new Key('api.memory_peak', $endTags)),
                Argument::type('int')
            )
            ->shouldBeCalled()
        ;

        $this->client->request('GET', '/');

        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testRequestOnIndexWithAUser(): void
    {
        $user = new \Fake\User();
        $statsDClient = $this->prophesize(Client::class);

        $this->setConnectedUser($user);
        $this->clientContainer()->set(StatsDClient::class, $statsDClient->reveal());

        $statsDClient
            ->increment(
                Argument::exact(
                    new Key(
                        'api.request',
                        new Tag\TagIterator(
                            [
                                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                                new Tag\NullTag(new Tag\Name('organization')),
                                new Tag\User($user->id()),
                                new GlobalTag('globalTag1', 'globalTagValue1'),
                                new GlobalTag('globalTag2', '2'),
                            ]
                        )
                    )
                ),
                1
            )
            ->shouldBeCalled()
        ;
        $endTags = new Tag\TagIterator(
            [
                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                new StatusCode(new Code(Response::HTTP_OK)),
                new Tag\User($user->id()),
                new Tag\NullTag(new Tag\Name('organization')),
                new GlobalTag('globalTag1', 'globalTagValue1'),
                new GlobalTag('globalTag2', '2'),
            ]
        );
        $statsDClient
            ->increment(
                Argument::exact(new Key('api.response', $endTags)),
                1
            )
            ->shouldBeCalled()
        ;
        $statsDClient
            ->timing(
                Argument::exact(new Key('api.time', $endTags)),
                Argument::type('float')
            )
            ->shouldBeCalled()
        ;
        $statsDClient
            ->gauge(
                Argument::exact(new Key('api.memory_peak', $endTags)),
                Argument::type('int')
            )
            ->shouldBeCalled()
        ;

        $this->client->request('GET', '/');

        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testRequestOnIndexWithAStandardUser(): void
    {
        $user = new \Fake\StandardUser();
        $statsDClient = $this->prophesize(Client::class);

        $this->setConnectedUser($user);
        $this->clientContainer()->set(StatsDClient::class, $statsDClient->reveal());

        $statsDClient
            ->increment(
                Argument::exact(
                    new Key(
                        'api.request',
                        new Tag\TagIterator(
                            [
                                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                                new Tag\Organization($user->organization()->id()),
                                new Tag\User($user->id()),
                                new GlobalTag('globalTag1', 'globalTagValue1'),
                                new GlobalTag('globalTag2', '2'),
                            ]
                        )
                    )
                ),
                1
            )
            ->shouldBeCalled()
        ;
        $endTags = new Tag\TagIterator(
            [
                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                new StatusCode(new Code(Response::HTTP_OK)),
                new Tag\User($user->id()),
                new Tag\Organization($user->organization()->id()),
                new GlobalTag('globalTag1', 'globalTagValue1'),
                new GlobalTag('globalTag2', '2'),
            ]
        );
        $statsDClient
            ->increment(
                Argument::exact(new Key('api.response', $endTags)),
                1
            )
            ->shouldBeCalled()
        ;
        $statsDClient
            ->timing(
                Argument::exact(new Key('api.time', $endTags)),
                Argument::type('float')
            )
            ->shouldBeCalled()
        ;
        $statsDClient
            ->gauge(
                Argument::exact(new Key('api.memory_peak', $endTags)),
                Argument::type('int')
            )
            ->shouldBeCalled()
        ;

        $this->client->request('GET', '/');

        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    private function setConnectedUser(\Shippeo\Heimdall\Domain\Model\User $user): void
    {
        /** @var FakeUserProvider $userProvider */
        $userProvider = $this->container()->get(UserProvider::class);
        $userProvider->setConnectedUser($user);
    }

    private function clientContainer(): ContainerInterface
    {
        $container = $this->client->getContainer();
        if ($container === null) {
            throw new \RuntimeException('container is not available');
        }

        return $container;
    }
}
