<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber;

use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
use Fake\StandardUser;
use Fake\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode as Code;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP as HTTPTemplate;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber\RequestSubscriber;
use Shippeo\Heimdall\Domain\Metric\Tag\Organization;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestSubscriberSpec extends ObjectBehavior
{
    function let(AddMetric $addMetric, UserProvider $userProvider)
    {
        $this->beConstructedWith($addMetric, $userProvider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestSubscriber::class);
    }

    function it_implements_EventSubscriberInterface()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_subscribes_to_the_expected_events()
    {
        $this::getSubscribedEvents()
            ->shouldBe(
                [
                    KernelEvents::REQUEST => [
                        ['onRequest', 1000],
                    ],
                    KernelEvents::RESPONSE => [
                        ['onResponse', 0],
                    ],
                ]
            )
        ;
    }

    function it_sends_a_response_metric(
        AddMetric $addMetric,
        UserProvider $userProvider,
        FilterResponseEvent $event,
        HttpFoundation\Request $request,
        HttpFoundation\Response $response,
        GetResponseEvent $requestEvent
    ) {
        $statusCode = new Code(123);
        $user = new User();
        $endpoint = 'fakeEndpoint';
        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $request->get('_route')->willReturn($endpoint);
        $response->getStatusCode()->willReturn($statusCode->value());

        $userProvider->connectedUser()->willReturn($user);

        $tags = new TagCollection(
            [
                new StatusCode($statusCode),
                new Endpoint($endpoint),
            ]
        );
        $addMetric
            ->__invoke(
                Argument::type(HTTPTemplate\Time::class),
                Argument::exact(
                    $tags->mergeWith(
                        new TagCollection([new \Shippeo\Heimdall\Domain\Metric\Tag\User($user->id())])
                    )
                )
            )
            ->shouldBeCalled()
        ;
        $addMetric
            ->__invoke(
                Argument::type(HTTPTemplate\MemoryPeak::class),
                Argument::exact($tags)
            )
            ->shouldBeCalled()
        ;

        $this->onRequest($requestEvent);
        $this->onResponse($event);
    }

    function it_sends_a_response_metric_without_an_endpoint(
        AddMetric $addMetric,
        UserProvider $userProvider,
        FilterResponseEvent $event,
        HttpFoundation\Request $request,
        HttpFoundation\Response $response,
        GetResponseEvent $requestEvent
    ) {
        $statusCode = new Code(123);
        $user = new User();
        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $request->get('_route')->willReturn(null);
        $response->getStatusCode()->willReturn($statusCode->value());

        $userProvider->connectedUser()->willReturn($user);

        $tags = new TagCollection(
            [
                new StatusCode($statusCode),
            ]
        );
        $addMetric
            ->__invoke(
                Argument::type(HTTPTemplate\Time::class),
                Argument::exact(
                    $tags->mergeWith(
                        new TagCollection([new \Shippeo\Heimdall\Domain\Metric\Tag\User($user->id())])
                    )
                )
            )
            ->shouldBeCalled()
        ;
        $addMetric
            ->__invoke(Argument::type(HTTPTemplate\MemoryPeak::class), Argument::exact($tags))
            ->shouldBeCalled()
        ;

        $this->onRequest($requestEvent);
        $this->onResponse($event);
    }

    function it_sends_a_response_metric_without_a_user(
        AddMetric $addMetric,
        UserProvider $userProvider,
        FilterResponseEvent $event,
        HttpFoundation\Request $request,
        HttpFoundation\Response $response,
        GetResponseEvent $requestEvent
    ) {
        $statusCode = new Code(123);
        $endpoint = 'fakeEndpoint';
        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $request->get('_route')->willReturn($endpoint);
        $response->getStatusCode()->willReturn($statusCode->value());

        $userProvider->connectedUser()->willReturn(null);

        $tags = new TagCollection(
            [
                new StatusCode($statusCode),
                new Endpoint($endpoint),
            ]
        );
        $addMetric
            ->__invoke(Argument::type(HTTPTemplate\Time::class), Argument::exact($tags))
            ->shouldBeCalled()
        ;
        $addMetric
            ->__invoke(Argument::type(HTTPTemplate\MemoryPeak::class), Argument::exact($tags))
            ->shouldBeCalled()
        ;

        $this->onRequest($requestEvent);
        $this->onResponse($event);
    }

    function it_sends_a_response_metric_with_a_standard_user(
        AddMetric $addMetric,
        UserProvider $userProvider,
        FilterResponseEvent $event,
        HttpFoundation\Request $request,
        HttpFoundation\Response $response,
        GetResponseEvent $requestEvent
    ) {
        $statusCode = new Code(123);
        $user = new StandardUser();
        $endpoint = 'fakeEndpoint';

        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $request->get('_route')->willReturn($endpoint);
        $response->getStatusCode()->willReturn($statusCode->value());

        $userProvider->connectedUser()->willReturn($user);

        $tags = new TagCollection(
            [
                new StatusCode($statusCode),
                new Endpoint($endpoint),
            ]
        );
        $addMetric
            ->__invoke(
                Argument::type(HTTPTemplate\Time::class),
                Argument::exact(
                    $tags->mergeWith(
                        new TagCollection(
                            [
                                new \Shippeo\Heimdall\Domain\Metric\Tag\User($user->id()),
                                new Organization($user->organization()->id()),
                            ]
                        )
                    )
                )
            )
            ->shouldBeCalled()
        ;
        $addMetric
            ->__invoke(Argument::type(HTTPTemplate\MemoryPeak::class), Argument::exact($tags))
            ->shouldBeCalled()
        ;

        $this->onRequest($requestEvent);
        $this->onResponse($event);
    }

    function it_sends_a_response_metric_with_a_standard_user_with_a_doctrine_data_collector(
        AddMetric $addMetric,
        UserProvider $userProvider,
        FilterResponseEvent $event,
        HttpFoundation\Request $request,
        HttpFoundation\Response $response,
        GetResponseEvent $requestEvent,
        DoctrineDataCollector $doctrineDataCollector
    ) {
        $statusCode = new Code(123);
        $user = new StandardUser();
        $endpoint = 'fakeEndpoint';
        $databaseDuration = 1234.56789;

        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $request->get('_route')->willReturn($endpoint);
        $response->getStatusCode()->willReturn($statusCode->value());

        $userProvider->connectedUser()->willReturn($user);

        $doctrineDataCollector->collect($request, $response)->shouldBeCalled();
        $doctrineDataCollector->getTime()->willReturn($databaseDuration);

        $tags = new TagCollection(
            [
                new StatusCode($statusCode),
                new Endpoint($endpoint),
            ]
        );
        $tagsWithUser = $tags->mergeWith(
            new TagCollection(
                [
                    new \Shippeo\Heimdall\Domain\Metric\Tag\User($user->id()),
                    new Organization($user->organization()->id()),
                ]
            )
        );
        $addMetric
            ->__invoke(
                Argument::type(HTTPTemplate\Time::class),
                Argument::exact($tagsWithUser)
            )
            ->shouldBeCalled()
        ;
        $addMetric
            ->__invoke(Argument::type(HTTPTemplate\MemoryPeak::class), Argument::exact($tags))
            ->shouldBeCalled()
        ;
        $addMetric
            ->__invoke(Argument::exact(new HTTPTemplate\DatabaseTime($databaseDuration)), Argument::exact($tagsWithUser))
            ->shouldBeCalled()
        ;

        $this->addDoctrineDataCollector($doctrineDataCollector);

        $this->onRequest($requestEvent);
        $this->onResponse($event);
    }
}
