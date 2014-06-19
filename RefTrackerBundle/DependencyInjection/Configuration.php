<?php

namespace Ars\RefTrackerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ars_ref_tracker');

        //set default config
        $rootNode
            ->children()
                ->scalarNode('query_param_name')
                    ->cannotBeEmpty()
                    ->defaultValue('ref')
                ->end()
                ->scalarNode('cookie_name')
                    ->cannotBeEmpty()
                    ->defaultValue('ars_ref_tracker')
                ->end()
                ->scalarNode('cookie_ttl')
                    ->cannotBeEmpty()
                    ->defaultValue(60 * 60 * 24 * 7)// 7 days
                ->end()
            ->end();

        return $treeBuilder;
    }
}
