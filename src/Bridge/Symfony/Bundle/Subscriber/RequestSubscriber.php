<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber;

use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\HTTP\StatusCode;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\Endpoint;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\HTTP\StatusCode as StatusCodeTag;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Template\HTTP as HTTPTemplate;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider;
use Shippeo\Heimdall\Domain\Metric\Tag\Organization;
use Shippeo\Heimdall\Domain\Metric\Tag\User;
use Shippeo\Heimdall\Domain\Metric\Timer\Time;
use Shippeo\Heimdall\Domain\Model\StandardUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestSubscriber implements EventSubscriberInterface
{
    /** @var AddMetric */
    private $addMetric;
    /** @var UserProvider */
    private $userProvider;

    /** @var Time */
    private $startTime;

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
                ['onRequest', 1000],
                ['onRequestPostAuthentication', 0],
            ],
            KernelEvents::RESPONSE => [
                ['onResponse', 0],
            ],
        ];
    }

    public function onRequest(GetResponseEvent $event): void
    {
        $this->startTime = Time::now();
    }

    public function onRequestPostAuthentication(GetResponseEvent $event): void
    {
        $tags = new TagCollection([]);
        $this->addEndpointTagToCollection($tags, $event->getRequest());
        $this->addUserTagToCollection($tags);

        ($this->addMetric)(new HTTPTemplate\Request(), $tags);
    }

    public function onResponse(FilterResponseEvent $event): void
    {
        $tags = new TagCollection(
            [
                new StatusCodeTag(
                    new StatusCode($event->getResponse()->getStatusCode())
                ),
            ]
        );
        $this->addEndpointTagToCollection($tags, $event->getRequest());
        $this->addUserTagToCollection($tags);

        ($this->addMetric)(new HTTPTemplate\Response(), $tags);

        ($this->addMetric)(new HTTPTemplate\Time($this->startTime, Time::now()), $tags);

        ($this->addMetric)(new HTTPTemplate\MemoryPeak(\memory_get_peak_usage(true)), $tags);
    }

    private function addEndpointTagToCollection(TagCollection $tags, SymfonyRequest $request): void
    {
        /** @var null|string $endpoint */
        $endpoint = $request->get('_route');
        if ($endpoint === null) {
            return;
        }

        $tags[] = new Endpoint($endpoint);
    }

    private function addUserTagToCollection(TagCollection $tags): void
    {
        $user = $this->userProvider->connectedUser();
        if ($user === null) {
            return;
        }

        $tags[] = new User($user->id());
        if ($user instanceof StandardUser) {
            $tags[] = new Organization($user->organization()->id());
        }
    }
}
