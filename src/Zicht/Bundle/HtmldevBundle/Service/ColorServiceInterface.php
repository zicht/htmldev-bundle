<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Provides a contract for turning a file into a readable palette
 * for use in a style guide.
 */
interface ColorServiceInterface
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
