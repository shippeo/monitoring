<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber;

use Fake\User;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Metric\Factory;
use Shippeo\Heimdall\Application\Metric\Template\Request;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Response;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber\RequestSubscriber;
use Shippeo\Heimdall\Domain\Database\Database;
use Shippeo\Heimdall\Domain\Metric\Metric;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestSubscriberSpec extends ObjectBehavior
{
    function let(Database $database, Factory $factory, UserProvider $userProvider)
    {
        $this->beConstructedWith(
            new AddMetric([$database->getWrappedObject()], $factory->getWrappedObject()),
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
        Factory $factory,
        UserProvider $userProvider,
        GetResponseEvent $event,
        HttpFoundation\Request $request,
        Metric $metric
    ) {
        $endpoint = 'fakeEndpoint';
        $user = new User();
        $template = new Request($user, $endpoint);

        $event->getRequest()->willReturn($request);
        $request->get('_route')->willReturn($endpoint);

        $userProvider->connectedUser()->willReturn($user);

        $factory->create($template)->willReturn($metric);
        $database->store($metric)->shouldBeCalled();

        $this->onRequest($event);
    }

    function it_sends_a_response_metric(
        Database $database,
        Factory $factory,
        UserProvider $userProvider,
        FilterResponseEvent $event,
        HttpFoundation\Request $request,
        HttpFoundation\Response $response,
        Metric $metric
    ) {
        $statusCode = new StatusCode(123);
        $user = new User();
        $endpoint = 'fakeEndpoint';
        $template = new Response($statusCode, $user, $endpoint);

        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);
        $request->get('_route')->willReturn($endpoint);
        $response->getStatusCode()->willReturn($statusCode->value());

        $userProvider->connectedUser()->willReturn($user);

        $factory->create($template)->willReturn($metric);
        $database->store($metric)->shouldBeCalled();

        $this->onResponse($event);
    }
}
