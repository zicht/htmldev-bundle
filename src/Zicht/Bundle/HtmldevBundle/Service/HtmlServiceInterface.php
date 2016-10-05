<?php
/**
 * @author Robert van Kempen <robert@zicht.nl>
 * @copyright Zicht online
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Defines a contract for services that manipulate HTML.
 *
 * @package Zicht\Bundle\HtmldevBundle\Service
 */
interface HtmlServiceInterface
{
    /**
     * Builds a string of class names based on supplied predicates.
     *
     * @param array ...$config
     *
     * @return string
     */
    public function getClasses(...$config);
}