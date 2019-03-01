<?php

declare(strict_types=1);

namespace Functional\Bridge\Symfony;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Database\StatsD\StatsD;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\DependencyInjection\MonitoringExtension;
use Shippeo\Heimdall\Infrastructure\Database\StatsDClient;

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
    /** @var array */
    private $globalTags = [
        'globalTagName1' => 'globalTagValue1',
        'globalTagName2' => 2,
    ];

    public function testExtensionLoad(): void
    {
        $this->load(
            [
                'statsD' => [
                    'host' => $this->host,
                    'port' => $this->port,
                ],
                'globalTags' => $this->globalTags,
            ]
        );

        $this->assertContainerBuilderHasService(StatsD::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(StatsD::class, 'monitoring.database');

        $this->assertContainerBuilderHasService(StatsDClient::class);

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            StatsD::class,
            'monitoring.database'
        );

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
