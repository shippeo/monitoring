<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber;

use Fake\StandardUser;
use Fake\Tag;
use Fake\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode as Code;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Request;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Response;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber\RequestSubscriber;
use Shippeo\Heimdall\Domain\Database\Database;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NullTag;
use Shippeo\Heimdall\Domain\Metric\Tag\Organization;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestSubscriberSpec extends ObjectBehavior
{
    /** @var TagCollection */
    private $globalTags;

    function let(Database $database, UserProvider $userProvider)
    {
        $this->globalTags = new TagCollection([new Tag\Tag1(), new Tag\Tag2()]);

        $this->beConstructedWith(
            new AddMetric([$database->getWrappedObject()], $this->globalTags),
            $userProvider
        );
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
                        ['onRequest', 0],
                    ],
                    KernelEvents::RESPONSE => [
                        ['onResponse', 0],
                    ],
                ]
            )
        ;
    }

    function it_sends_a_request_metric(
        Database $database,
        UserProvider $userProvider,
        GetResponseEvent $event,
        HttpFoundation\Request $request
    ) {
        $endpoint = 'fakeEndpoint';
        $user = new User();
        $template = new Request();

        $event->getRequest()->willReturn($request);
        $request->get('_route')->willReturn($endpoint);

        $userProvider->connectedUser()->willReturn($user);

        $database
            ->store(
                Argument::exact(
                    new Counter(
                        $template->name(),
                        $template->value(),
                        (
                            new TagCollection(
                                [
                                    new Endpoint($endpoint),
                                    new NullTag(new Name('organization')),
                                    new \Shippeo\Heimdall\Domain\Metric\Tag\User($user->id()),
                                ]
                            )
                        )
                            ->mergeWith($this->globalTags)
                            ->getIterator()
                    )
                )
            )
            ->shouldBeCalled()
        ;

        $this->onRequest($event);
    }

    function it_sends_a_request_metric_without_a_user(
        Database $database,
        UserProvider $userProvider,
        GetResponseEvent $event,
        HttpFoundation\Request $request
    ) {
        $endpoint = 'fakeEndpoint';
        $template = new Request();

        $event->getRequest()->willReturn($request);
        $request->get('_route')->willReturn($endpoint);

        $userProvider->connectedUser()->willReturn(null);

        $database
            ->store(
                Argument::exact(
                    new Counter(
                        $template->name(),
                        $template->value(),
                        (
                            new TagCollection(
                                [
                                    new Endpoint($endpoint),
                                    new NullTag(new Name('organization')),
                                    new NullTag(new Name('user')),
                                ]
                            )
                        )
                            ->mergeWith($this->globalTags)
                            ->getIterator()
                    )
                )
            )
            ->shouldBeCalled()
        ;

        $this->onRequest($event);
    }

    function it_sends_a_request_metric_with_a_standard_user(
        Database $database,
        UserProvider $userProvider,
        GetResponseEvent $event,
        HttpFoundation\Request $request
    ) {
        $endpoint = 'fakeEndpoint';
        $user = new StandardUser();
        $template = new Request();

        $event->getRequest()->willReturn($request);
        $request->get('_route')->willReturn($endpoint);

        $userProvider->connectedUser()->willReturn($user);

        $database
            ->store(
                Argument::exact(
                    new Counter(
                        $template->name(),
                        $template->value(),
                        (
                        new TagCollection(
                            [
                                new Endpoint($endpoint),
                                new Organization($user->organization()->id()),
                                new \Shippeo\Heimdall\Domain\Metric\Tag\User($user->id()),
                            ]
                        )
                        )
                            ->mergeWith($this->globalTags)
                            ->getIterator()
                    )
                )
            )
            ->shouldBeCalled()
        ;

        $this->onRequest($event);
    }

    function it_sends_a_response_metric(
        Database $database,
        UserProvider $userProvider,
        FilterResponseEvent $event,
        HttpFoundation\Request $request,
        HttpFoundation\Response $response
    ) {
        $statusCode = new Code(123);
        $user = new User();
        $endpoint = 'fakeEndpoint';
        $template = new Response();

        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $request->get('_route')->willReturn($endpoint);
        $response->getStatusCode()->willReturn($statusCode->value());

        $userProvider->connectedUser()->willReturn($user);

        $database
            ->store(
                Argument::exact(
                    new Counter(
                        $template->name(),
                        $template->value(),
                        (
                            new TagCollection(
                                [
                                    new Endpoint($endpoint),
                                    new StatusCode($statusCode),
                                    new \Shippeo\Heimdall\Domain\Metric\Tag\User($user->id()),
                                    new NullTag(new Name('organization')),
                                ]
                            )
                        )
                            ->mergeWith($this->globalTags)
                            ->getIterator()
                    )
                )
            )
            ->shouldBeCalled()
        ;

        $this->onResponse($event);
    }

    function it_sends_a_response_metric_without_an_endpoint(
        Database $database,
        UserProvider $userProvider,
        FilterResponseEvent $event,
        HttpFoundation\Request $request,
        HttpFoundation\Response $response
    ) {
        $statusCode = new Code(123);
        $user = new User();
        $template = new Response();

        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $request->get('_route')->willReturn(null);
        $response->getStatusCode()->willReturn($statusCode->value());

        $userProvider->connectedUser()->willReturn($user);

        $database
            ->store(
                Argument::exact(
                    new Counter(
                        $template->name(),
                        $template->value(),
                        (
                            new TagCollection(
                                [
                                    new NullTag(new Name('endpoint')),
                                    new StatusCode($statusCode),
                                    new \Shippeo\Heimdall\Domain\Metric\Tag\User($user->id()),
                                    new NullTag(new Name('organization')),
                                ]
                            )
                        )
                            ->mergeWith($this->globalTags)
                            ->getIterator()
                    )
                )
            )
            ->shouldBeCalled()
        ;

        $this->onResponse($event);
    }

    function it_sends_a_response_metric_without_a_user(
        Database $database,
        UserProvider $userProvider,
        FilterResponseEvent $event,
        HttpFoundation\Request $request,
        HttpFoundation\Response $response
    ) {
        $statusCode = new Code(123);
        $endpoint = 'fakeEndpoint';
        $template = new Response();

        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $request->get('_route')->willReturn($endpoint);
        $response->getStatusCode()->willReturn($statusCode->value());

        $userProvider->connectedUser()->willReturn(null);

        $database
            ->store(
                Argument::exact(
                    new Counter(
                        $template->name(),
                        $template->value(),
                        (
                            new TagCollection(
                                [
                                    new Endpoint($endpoint),
                                    new StatusCode($statusCode),
                                    new NullTag(new Name('user')),
                                    new NullTag(new Name('organization')),
                                ]
                            )
                        )
                            ->mergeWith($this->globalTags)
                            ->getIterator()
                    )
                )
            )
            ->shouldBeCalled()
        ;

        $this->onResponse($event);
    }

    function it_sends_a_response_metric_with_a_standard_user(
        Database $database,
        UserProvider $userProvider,
        FilterResponseEvent $event,
        HttpFoundation\Request $request,
        HttpFoundation\Response $response
    ) {
        $statusCode = new Code(123);
        $user = new StandardUser();
        $endpoint = 'fakeEndpoint';
        $template = new Response();

        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $request->get('_route')->willReturn($endpoint);
        $response->getStatusCode()->willReturn($statusCode->value());

        $userProvider->connectedUser()->willReturn($user);

        $database
            ->store(
                Argument::exact(
                    new Counter(
                        $template->name(),
                        $template->value(),
                        (
                            new TagCollection(
                                [
                                    new Endpoint($endpoint),
                                    new StatusCode($statusCode),
                                    new \Shippeo\Heimdall\Domain\Metric\Tag\User($user->id()),
                                    new Organization($user->organization()->id()),
                                ]
                            )
                        )
                            ->mergeWith($this->globalTags)
                            ->getIterator()
                    )
                )
            )
            ->shouldBeCalled()
        ;

        $this->onResponse($event);
    }
}
