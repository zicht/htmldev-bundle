<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */
namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Provides a contract for turning a file into a readable palette to use in the style guide.
 */
interface ColorServiceInterface
{
    /**
     * Looks in the supplied Sass file for sections and corresponding colors,
     * and turns them into an array that can be used to display the entire palette.
     *
     * @param string $file
     *
     * @return array
     */
    public function getColorGroups($file);
}
