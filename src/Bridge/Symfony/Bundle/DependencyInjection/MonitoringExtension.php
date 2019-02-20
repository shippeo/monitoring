<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\DependencyInjection;

use Shippeo\Heimdall\Application\Database\StatsD\StatsD;
use Shippeo\Heimdall\Application\Metric\Factory;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\DependencyInjection\Tag\GlobalTagFactory;
use Shippeo\Heimdall\Infrastructure\Database\StatsDClient;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class MonitoringExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        (new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config')))
            ->load('services.yml')
        ;

        $container
            ->register(Factory::class)
            ->setArguments(
                [
                    (new Definition(TagCollection::class, [$config['globalTags']]))
                        ->setFactory(GlobalTagFactory::class.'::create'),
                ]
            )
        ;

        $container
            ->register(StatsDClient::class)
            ->setFactory(StatsDClient::class.'::fromArray')
            ->setArguments([$config['statsD']])
            ->setPublic(true) // this is for testing purpose only
        ;

        $container
            ->register(StatsD::class)
            ->setAutowired(true)
            ->addTag('monitoring.database')
        ;
    }
}
