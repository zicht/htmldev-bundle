<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\DependencyInjection;

use Symfony\Component\Cache\Simple\ApcuCache;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Cache\Simple\ArrayCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Zicht\Bundle\HtmldevBundle\Controller\HtmldevController;
use Zicht\Bundle\HtmldevBundle\Service\AbstractDataLoader;
use Zicht\Bundle\HtmldevBundle\Service\SvgService;
use Zicht\Bundle\HtmldevBundle\Twig\ImageExtension;

/**
 * Zicht Htmldev bundle extension.
 */
class ZichtHtmldevExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $htmldevDir = $container->getParameter('htmldev.directory');
        $paths = array_map(
            static function ($path) use ($htmldevDir) {
                return str_replace('%htmldev.directory%', $htmldevDir, $path);
            },
            $config['paths']
        );

        $container->getDefinition(AbstractDataLoader::class)->replaceArgument(0, $paths);
        $container->getDefinition(ImageExtension::class)->replaceArgument(0, $paths['images_icons']);
        $container->getDefinition(SvgService::class)->replaceArgument(0, $paths['svg_service_base_dir']);

        if (null !== $cache = $this->getCacheReference($config, $container)) {
            $container->getDefinition(SvgService::class)->replaceArgument(1, $cache);
        }

        if (isset($config['styleguide']) && is_array($config['styleguide'])) {
            $container->getDefinition(HtmldevController::class)->addMethodCall('setStyleguideConfig', [$config['styleguide']]);
        }
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @return null|Reference
     */
    private function getCacheReference(array $config, ContainerBuilder $container)
    {
        switch ($config['svg_cache']['type']) {
            case 'service':
                return new Reference($config['svg_cache']['name']);
                break;
            case 'auto':
                switch ($config['svg_cache']['name']) {
                    case 'file':
                        $definition = new Definition(FilesystemCache::class, ['svg_render', 0, '%kernel.cache_dir%']);
                        break;
                    case 'array':
                        $definition = new Definition(ArrayCache::class, [0, false]);
                        break;
                    case 'apcu':
                        $definition = new Definition(ApcuCache::class, ['svg_render']);
                        break;
                    default:
                        throw new \InvalidArgumentException('invalid cache type ' . $config['svg_cache']['name'] . ' for type auto');
                }
                $container->setDefinition('htmldev.svg_cache', $definition)->setPublic(false);
                return new Reference('htmldev.svg_cache');
                break;
        }
        return null;
    }
}
