<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
use Shippeo\Heimdall\Application\Database\StatsD\StatsD;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber\RequestSubscriber;
use Shippeo\Heimdall\Infrastructure\Database\StatsDClient;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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

        $container->setParameter('globalTags', $config['globalTags']);

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

        if (\class_exists(DoctrineDataCollector::class)
            && $container->hasDefinition('data_collector.doctrine')
        ) {
            $container
                ->getDefinition(RequestSubscriber::class)
                ->addMethodCall('addDoctrineDataCollector', [$container->getDefinition('data_collector.doctrine')])
            ;
        }
    }
}
