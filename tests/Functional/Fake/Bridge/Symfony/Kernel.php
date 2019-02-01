<?php

declare(strict_types=1);

namespace Functional\Fake\Bridge\Symfony;

use Shippeo\Heimdall\Bridge\Symfony\Bundle\MonitoringBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollectionBuilder;

final class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new MonitoringBundle(),
            new Bundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->loadFromExtension(
            'framework',
            [
                'secret' => 'MySecretKey',
            ]
        );

        $container->loadFromExtension(
            'monitoring',
            [
                'statsD' => [
                    'host' => 'fakeHost',
                    'port' => 4242,
                ],
            ]
        );
    }
}
