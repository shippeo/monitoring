<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;

interface TagCollectorInterface
{
    /**
     * @return Tag[]
     */
    public function http(HTTPContext $context): array;

    /**
     * @return Tag[]
     */
    public function cli(CliContext $context): array;
}
