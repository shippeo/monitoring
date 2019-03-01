<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber;

use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Metric\Template\Request;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\Response;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestSubscriber implements EventSubscriberInterface
{
    /** @var AddMetric */
    private $addMetric;
    /** @var UserProvider */
    private $userProvider;

    public function __construct(AddMetric $addMetric, UserProvider $userProvider)
    {
        $this->addMetric = $addMetric;
        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onRequest', 0],
            ],
            KernelEvents::RESPONSE => [
                ['onResponse', 0],
            ],
        ];
    }

    public function onRequest(GetResponseEvent $event): void
    {
        ($this->addMetric)(
            new Request(
                $this->userProvider->connectedUser(),
                $event->getRequest()->get('_route')
            )
        );
    }

    public function onResponse(FilterResponseEvent $event): void
    {
        ($this->addMetric)(
            new Response(
                new StatusCode($event->getResponse()->getStatusCode()),
                $this->userProvider->connectedUser(),
                $event->getRequest()->get('_route')
            )
        );
    }
}
