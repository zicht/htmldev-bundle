<?php
/**
 * @author Robert van Kempen <robert@zicht.nl>
 * @copyright Zicht online
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Provides a contract for turning a file into a readable palette
 * for use in a style guide.
 *
 * @package Zicht\Bundle\HtmldevBundle\Service
 */
interface IColorService
{
    /**
     * @param string $hexColor
     * @return number
     */
    public function getLuminance($hexColor);

    /**
     * @param string $file
     * @return mixed
     */
    public function getColorGroups($file);
}