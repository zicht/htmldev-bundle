<?php declare(strict_types=1);
/**
 * @copyright Zicht online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Builds menu's for htmldev pages.
 */
class MenuBuilder implements MenuBuilderInterface
{
    /** @var FactoryInterface */
    private $factory;

    /** @var DataLoaderInterface */
    private $yamlLoader;

    public function __construct(FactoryInterface $factory, DataLoaderInterface $yamlLoader)
    {
        $this->factory = $factory;
        $this->yamlLoader = $yamlLoader;
    }

    /**
     * @return ItemInterface
     */
    public function createStyleguideMenu(RequestStack $requestStack)
    {
        $items = $this->yamlLoader->loadData('data/styleguide', 'navigation.yml');

        return $this->buildMenuFromYml($items, $this->factory->createItem('root'));
    }

    /**
     * @param array $items
     * @return ItemInterface
     */
    protected function buildMenuFromYml($items, ItemInterface $parent)
    {
        foreach ($items as $i) {
            $parent->addChild($i['title'], ['uri' => $i['uri']]);

            if (!array_key_exists('items', $i) || count($i['items']) === 0) {
                continue;
            }

            $this->buildMenuFromYml($i['items'], $parent[$i['title']]);
        }

        return $parent;
    }
}
