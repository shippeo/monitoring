<?php

declare(strict_types=1);

namespace Functional\Fake\Bridge\Symfony;

use Functional\Fake\Bridge\Symfony\Provider\UserProvider as FakeUserProvider;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\MonitoringBundle;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Provider\UserProvider;
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
        $routes->import(__DIR__.'/Controller/DefaultController.php', '/', 'annotation');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container
            ->register(UserProvider::class, FakeUserProvider::class)
            ->setPublic(true)
        ;

        $container->loadFromExtension(
            'framework',
            [
                'secret' => 'MySecretKey',
                'test' => null,
            ]
        );

        $container->loadFromExtension(
            'monitoring',
            [
                'statsD' => [
                    'host' => 'fakeHost',
                    'port' => 4242,
                ],
                'globalTags' => [
                    'globalTag1' => 'globalTagValue1',
                    'globalTag2' => 2,
                ],
            ]
        );
    }
}
