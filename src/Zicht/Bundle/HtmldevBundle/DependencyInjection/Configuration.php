<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('htmldev');

        $rootNode
            ->children()
                ->arrayNode('svg_cache')
                    ->addDefaultsIfNotSet()
                        ->info('This should be a service prefixed with @ fro0r an service or one of "file", "array", or "apcu" values.')
                        ->beforeNormalization()
                        ->ifString()
                        ->then(function($value) {
                            if ($value[0] === '@') {
                                return [
                                    'type' => 'service',
                                    'name' => substr($value, 1),
                                ];
                            } else {
                                return [
                                    'type' => 'auto',
                                    'name' => $value,
                                ];
                            }
                        })
                        ->end()
                        ->children()
                            ->enumNode('type')
                                ->values(['service', 'auto'])
                                ->defaultValue('auto')
                            ->end()
                            ->scalarNode('name')
                                ->isRequired()
                                ->defaultValue('file')
                            ->end()
                        ->end()
                        ->validate()
                        ->always(function($v) {
                            if ('auto' === $v['type'] && !in_array($v['name'], ['file', 'array', 'apcu'])) {
                                throw new \InvalidArgumentException('Invalid svg_cache value, expected one of "file", "array" or "apcu" got ' . $v['name']);
                            }
                            return $v;
                        })
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
