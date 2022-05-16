<?php
/**
 * @copyright Zicht Online <http://zicht.nl>
 */
namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Contract for styleguide data loaders.
 */
interface DataLoaderInterface
{
    /**
     * Gets the array contents of the files inside the given directory,
     * optionally only from files matching the given name pattern.
     *
     * @param string $directory
     * @param string $namePattern The name pattern of the files to load. Can include directories.
     * @return string|array
     */
    public function loadData($directory, $namePattern = '*');
}
