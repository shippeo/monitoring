<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\CliContext;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\HTTPContext;
use Shippeo\Heimdall\Domain\Metric\Template\Template;

interface TemplateCollectorInterface
{
    /**
     * @return Template[]
     */
    public function http(HTTPContext $context): array;

    /**
     * @return Template[]
     */
    public function cli(CliContext $context): array;
}
