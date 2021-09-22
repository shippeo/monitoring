<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\DependencyInjection\Tag;

use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\GlobalTag;

final class GlobalTagFactory
{
    /** @param array<int|string, scalar> $tags */
    public static function create(array $tags): TagCollection
    {
        $collection = new TagCollection([]);
        foreach ($tags as $name => $value) {
            if (\is_string($name) && $name !== '') {
                $collection[] = new GlobalTag($name, (string) $value);
            }
        }

        return $collection;
    }
}
