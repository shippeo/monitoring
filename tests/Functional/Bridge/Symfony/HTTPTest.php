<?php

declare(strict_types=1);

namespace Functional\Bridge\Symfony;

use Functional\Fake\Bridge\Symfony\Provider\UserProvider as FakeUserProvider;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Shippeo\Heimdall\Application\Database\StatsD\Client;
use Shippeo\Heimdall\Application\Database\StatsD\Key;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode as Code;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\GlobalTag;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider;
use Shippeo\Heimdall\Domain\Metric\Tag;
use Shippeo\Heimdall\Infrastructure\Database\StatsDClient;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
final class HTTPTest extends WebTestCase
{
    use ProphecyTrait;

    /** @var KernelBrowser */
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

        $this->client->getContainer()->set(StatsDClient::class, $statsDClient->reveal());

        $statsDClient
            ->timing(
                Argument::exact(
                    new Key(
                        'http.time',
                        new Tag\TagIterator(
                            [
                                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                                new StatusCode(new Code(Response::HTTP_OK)),
                                new Tag\NullTag(new Tag\Name('user')),
                                new Tag\NullTag(new Tag\Name('organization')),
                                new GlobalTag('globalTag1', 'globalTagValue1'),
                                new GlobalTag('globalTag2', '2'),
                            ]
                        )
                    )
                ),
                Argument::type('float')
            )
            ->shouldBeCalled()
        ;
        $statsDClient
            ->gauge(
                Argument::exact(
                    new Key(
                        'http.memory_peak',
                        new Tag\TagIterator(
                            [
                                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                                new StatusCode(new Code(Response::HTTP_OK)),
                                new GlobalTag('globalTag1', 'globalTagValue1'),
                                new GlobalTag('globalTag2', '2'),
                            ]
                        )
                    )
                ),
                Argument::type('float')
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
        $this->client->getContainer()->set(StatsDClient::class, $statsDClient->reveal());

        $statsDClient
            ->timing(
                Argument::exact(
                    new Key(
                        'http.time',
                        new Tag\TagIterator(
                            [
                                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                                new StatusCode(new Code(Response::HTTP_OK)),
                                new Tag\User($user->id()),
                                new Tag\NullTag(new Tag\Name('organization')),
                                new GlobalTag('globalTag1', 'globalTagValue1'),
                                new GlobalTag('globalTag2', '2'),
                            ]
                        )
                    )
                ),
                Argument::type('float')
            )
            ->shouldBeCalled()
        ;
        $statsDClient
            ->gauge(
                Argument::exact(
                    new Key(
                        'http.memory_peak',
                        new Tag\TagIterator(
                            [
                                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                                new StatusCode(new Code(Response::HTTP_OK)),
                                new GlobalTag('globalTag1', 'globalTagValue1'),
                                new GlobalTag('globalTag2', '2'),
                            ]
                        )
                    )
                ),
                Argument::type('float')
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
        $this->client->getContainer()->set(StatsDClient::class, $statsDClient->reveal());

        $statsDClient
            ->timing(
                Argument::exact(
                    new Key(
                        'http.time',
                        new Tag\TagIterator(
                            [
                                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                                new StatusCode(new Code(Response::HTTP_OK)),
                                new Tag\User($user->id()),
                                new Tag\Organization($user->organization()->id()),
                                new GlobalTag('globalTag1', 'globalTagValue1'),
                                new GlobalTag('globalTag2', '2'),
                            ]
                        )
                    )
                ),
                Argument::type('float')
            )
            ->shouldBeCalled()
        ;
        $statsDClient
            ->gauge(
                Argument::exact(
                    new Key(
                        'http.memory_peak',
                        new Tag\TagIterator(
                            [
                                new \Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint('index'),
                                new StatusCode(new Code(Response::HTTP_OK)),
                                new GlobalTag('globalTag1', 'globalTagValue1'),
                                new GlobalTag('globalTag2', '2'),
                            ]
                        )
                    )
                ),
                Argument::type('float')
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
}
