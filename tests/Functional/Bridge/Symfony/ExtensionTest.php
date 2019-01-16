<?php

declare(strict_types=1);

namespace Functional\Bridge\Symfony;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Shippeo\Heimdall\Application\Database\StatsD\StatsD;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\DependencyInjection\MonitoringExtension;
use Shippeo\Heimdall\Domain\AddMetric;
use Shippeo\Heimdall\Domain\Database\DatabaseIterator;
use Shippeo\Heimdall\Infrastructure\Database\StatsDClient;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 * @coversNothing
 */
final class ExtensionTest extends AbstractExtensionTestCase
{
    /** @var string */
    private $host = 'fakeStatsDHost';
    /** @var int */
    private $port = 9874;

    public function testExtensionLoad(): void
    {
        $this->load(
            [
                'statsD' => [
                    'host' => $this->host,
                    'port' => $this->port,
                ],
            ]
        );

        $this->assertContainerBuilderHasService(StatsD::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            StatsD::class,
            0,
            (new Definition(StatsDClient::class, [['host' => $this->host, 'port' => $this->port]]))->setFactory(StatsDClient::class.'::fromArray')
        );
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            StatsD::class,
            'monitoring.database'
        );

        $this->assertContainerBuilderHasService(DatabaseIterator::class);
        $this->assertContainerBuilderHasService(AddMetric::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions(): array
    {
        return [
            new MonitoringExtension(),
        ];
    }
}
