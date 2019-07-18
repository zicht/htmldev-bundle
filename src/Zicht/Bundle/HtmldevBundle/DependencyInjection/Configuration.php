<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
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

        $this->buildStyleguideConfigTree($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition|NodeDefinition $rootNode
     */
    private function buildStyleguideConfigTree($rootNode)
    {
        if (isset($_SERVER['APPLICATION_ENV']) && 'production' !== $_SERVER['APPLICATION_ENV']) {
            // Don't do any effort to validate the styleguide tree in production env.
            return;
        }

        $rootNode
            ->children()
                ->arrayNode('styleguide')
                    ->fixXmlConfig('asset')
                    ->children()
                        ->arrayNode('assets')
                            ->info(
                                'These assets will be loaded additionally in the HTMLDEV Styleguide pages. Configure your website\'s stylesheet(s) and javascript(s) here.'
                            )
                            ->requiresAtLeastOneElement()
                            ->prototype('array')
                                ->info('Set at type and define one of path, url or body')
                                ->children()
                                    ->enumNode('type')->values(['stylesheet', 'script'])->isRequired()->end()
                                    ->scalarNode('path')->info('Will be rendered using asset()')->end()
                                    ->scalarNode('url')->info('For external assets')->end()
                                    ->scalarNode('body')->info('To insert snippets directly into the document')->end()
                                ->end()
                                ->validate()
                                    ->ifTrue(
                                        static function ($asset) {
                                            return 0 === count(array_intersect(['path', 'url', 'body'], array_keys($asset)));
                                        }
                                    )
                                    ->thenInvalid('Invalid Styleguide asset configured. A path, url or body must be configured. %s')
                                ->end()
                                ->validate()
                                    ->ifTrue(
                                        static function ($asset) {
                                            return 1 < count(array_intersect(['path', 'url', 'body'], array_keys($asset)));
                                        }
                                    )
                                    ->thenInvalid('Invalid asset configured. Only one of path, url or body must be configured. %s')
                                ->end()
                                ->validate()
                                    ->ifTrue(
                                        static function ($asset) {
                                            return 0 === count(array_intersect(['path', 'url', 'body'], array_keys(array_filter($asset))));
                                        }
                                    )
                                    ->thenInvalid('Invalid asset configured. Path/url/body cannot be empty. %s')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
