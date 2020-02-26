<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag\ChainTagCollector;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Tag\TagCollectorInterface;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\ChainTemplateCollector;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Collector\Template\TemplateCollectorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class MonitoringBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->registerForAutoconfiguration(TagCollectorInterface::class)->addTag(ChainTagCollector::class);
        $container->registerForAutoconfiguration(TemplateCollectorInterface::class)->addTag(ChainTemplateCollector::class);
    }
}
