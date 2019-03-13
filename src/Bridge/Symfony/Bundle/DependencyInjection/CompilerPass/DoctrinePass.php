<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\DependencyInjection\CompilerPass;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber\RequestSubscriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DoctrinePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('doctrine.dbal.logger.profiling.default')) {
            return;
        }

        $container
            ->getDefinition(RequestSubscriber::class)
            ->addMethodCall(
                'addDoctrineDataCollector',
                [$container->getDefinition('data_collector.doctrine')]
            )
        ;
    }
}
