<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber;

use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
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
    /** @var null|DoctrineDataCollector */
    private $doctrineDataCollector;
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
            ],
            KernelEvents::RESPONSE => [
                ['onResponse', 0],
            ],
        ];
    }

    public function addDoctrineDataCollector(DoctrineDataCollector $dataCollector): void
    {
        $this->doctrineDataCollector = $dataCollector;
    }

    public function onRequest(GetResponseEvent $event): void
    {
        $this->startTime = Time::now();
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
        /** @var TagCollection $tagsWithUser */
        $tagsWithUser = $tags->mergeWith($this->userTags());

        ($this->addMetric)(new HTTPTemplate\Time($this->startTime, Time::now()), $tagsWithUser);
        ($this->addMetric)(new HTTPTemplate\MemoryPeak(\memory_get_peak_usage(true)), $tags);

        if ($this->doctrineDataCollector !== null) {
            $this->doctrineDataCollector->collect($event->getRequest(), $event->getResponse());
            ($this->addMetric)(new HTTPTemplate\DatabaseTime((float) $this->doctrineDataCollector->getTime()), $tagsWithUser);
        }
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

    private function userTags(): TagCollection
    {
        $user = $this->userProvider->connectedUser();
        if ($user === null) {
            return new TagCollection([]);
        }

        $tags = new TagCollection([new User($user->id())]);
        if ($user instanceof StandardUser) {
            $tags[] = new Organization($user->organization()->id());
        }

        return $tags;
    }
}
