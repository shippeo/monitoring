<?php

declare(strict_types=1);

namespace Functional\Bridge\Symfony;

use Functional\Fake\Bridge\Symfony\Provider\UserProvider as FakeUserProvider;
use Prophecy\Argument;
use Shippeo\Heimdall\Application\Database\StatsD\Client;
use Shippeo\Heimdall\Application\Database\StatsD\Key;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\GlobalTag;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider;
use Shippeo\Heimdall\Domain\Metric\Tag;
use Shippeo\Heimdall\Infrastructure\Database\StatsDClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @internal
 * @coversNothing
 */
final class RequestTest extends WebTestCase
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

        $statsDClient->increment(
            Argument::exact(
                new Key(
                    'api.request',
                    new Tag\TagIterator(
                        [
                            new \Shippeo\Heimdall\Application\Metric\Tag\Endpoint('index'),
                            new Tag\Organization(null),
                            new Tag\User(null),
                            new GlobalTag('globalTag1', 'globalTagValue1'),
                            new GlobalTag('globalTag2', '2'),
                        ]
                    )
                )
            ),
            1
        )->shouldBeCalled();

        $this->client->request('GET', '/');

        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testRequestOnIndexWithAUser(): void
    {
        $user = new \Fake\User();
        $statsDClient = $this->prophesize(Client::class);

        $this->setConnectedUser($user);
        $this->clientContainer()->set(StatsDClient::class, $statsDClient->reveal());

        $statsDClient->increment(
            Argument::exact(
                new Key(
                    'api.request',
                    new Tag\TagIterator(
                        [
                            new \Shippeo\Heimdall\Application\Metric\Tag\Endpoint('index'),
                            new Tag\Organization(null),
                            new Tag\User($user->id()),
                            new GlobalTag('globalTag1', 'globalTagValue1'),
                            new GlobalTag('globalTag2', '2'),
                        ]
                    )
                )
            ),
            1
        )->shouldBeCalled();

        $this->client->request('GET', '/');

        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testRequestOnIndexWithAStandardUser(): void
    {
        $user = new \Fake\StandardUser();
        $statsDClient = $this->prophesize(Client::class);

        $this->setConnectedUser($user);
        $this->clientContainer()->set(StatsDClient::class, $statsDClient->reveal());

        $statsDClient->increment(
            Argument::exact(
                new Key(
                    'api.request',
                    new Tag\TagIterator(
                        [
                            new \Shippeo\Heimdall\Application\Metric\Tag\Endpoint('index'),
                            new Tag\Organization($user->organization()->id()),
                            new Tag\User($user->id()),
                            new GlobalTag('globalTag1', 'globalTagValue1'),
                            new GlobalTag('globalTag2', '2'),
                        ]
                    )
                )
            ),
            1
        )->shouldBeCalled();

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
