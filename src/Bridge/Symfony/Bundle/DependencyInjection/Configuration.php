<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('monitoring');
        /** @var ArrayNodeDefinition $globalTagsNode */
        $globalTagsNode = (new ArrayNodeDefinition('globalTags'))
            ->scalarPrototype()
            ->end()
        ;

        /** @var ArrayNodeDefinition $root */
        $root = $treeBuilder->getRootNode();
        $root
            ->append(
                (new ArrayNodeDefinition('statsD'))
                    ->info('Informations about the statsd server')
                    ->addDefaultsIfNotSet()
                    ->append(
                        (new ScalarNodeDefinition('host'))
                            ->isRequired()
                            ->cannotBeEmpty()
                    )
                    ->append(
                        (new ScalarNodeDefinition('port'))
                            ->defaultValue(8125)
                            ->isRequired()
                            ->cannotBeEmpty()
                    )
            )
            ->append(
                $globalTagsNode
            )
        ;

        return $treeBuilder;
    }
}
