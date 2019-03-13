<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric;

use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\NullTag;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;
use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;

final class Factory
{
    /** @var TagIterator */
    private $globalTags;

    public function __construct(TagIterator $globalTags)
    {
        $this->globalTags = $globalTags;
    }

    public function create(Template\Template $template, TagIterator $tags): Metric
    {
        $allTags = $this->prepareTags($template, $tags);

        if ($template instanceof Template\Counter) {
            return $this->createCounter($template, $allTags);
        }
        if ($template instanceof Template\Timer) {
            return $this->createTimer($template, $allTags);
        }
        if ($template instanceof Template\Gauge) {
            return $this->createGauge($template, $allTags);
        }

        throw new \LogicException('not supported template');
    }

    private function prepareTags(Template\Template $template, TagIterator $tags): TagIterator
    {
        $filteredTags = [];
        foreach ($template->tags() as $tagName) {
            $filteredTags[(string) $tagName] = $this->findTagWithName($tagName, $tags);
        }
        foreach ($this->globalTags as $tag) {
            $filteredTags[(string) $tag->name()] = $tag;
        }

        return new TagIterator(\array_values($filteredTags));
    }

    private function findTagWithName(Name $name, TagIterator $tags): Tag
    {
        foreach ($tags as $tag) {
            if ($name->equalTo($tag->name())) {
                return $tag;
            }
        }

        return new NullTag($name);
    }

    private function createCounter(Template\Counter $template, TagIterator $tags): Counter
    {
        return new Counter(
            $template->name(),
            $template->value(),
            $tags
        );
    }

    private function createTimer(Template\Timer $template, TagIterator $tags): Timer
    {
        return new Timer(
            $template->name(),
            $template->duration(),
            $tags
        );
    }

    private function createGauge(Template\Gauge $template, TagIterator $tags): Gauge
    {
        return new Gauge(
            $template->name(),
            $template->value(),
            $tags
        );
    }
}
