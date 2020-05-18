<?php
/**
 * @copyright Zicht online <http://zicht.nl>
 */
namespace Zicht\Bundle\HtmldevBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Contract for menu builders.
 */
interface MenuBuilderInterface
{
    /**
     * @param RequestStack $requestStack
     * @return ItemInterface
     */
    public function createStyleguideMenu(RequestStack $requestStack);
}
