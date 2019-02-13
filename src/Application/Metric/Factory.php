<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Application\Metric;

use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Application\Metric\Template\Template;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Metric;

class Factory
{
    /** @var TagCollection */
    private $globalTags;

    public function __construct(TagCollection $globalTags)
    {
        $this->globalTags = $globalTags;
    }

    public function create(Template $template): Metric
    {
        if ($template->type() === Counter::class) {
            /** @var TagCollection $tags */
            $tags = $template->tags()->mergeWith($this->globalTags);

            return new Counter(
                $template->name(),
                $template->value(),
                $tags->getIterator()
            );
        }

        throw new \LogicException($template->type().' is not handled.');
    }
}
