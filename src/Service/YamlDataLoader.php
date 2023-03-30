<?php declare(strict_types=1);
/**
 * @copyright Zicht Online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

use Symfony\Component\Yaml\Yaml;

/**
 * Loads Yaml data from a directory in the styleguide.
 */
class YamlDataLoader extends AbstractDataLoader implements DataLoaderInterface
{
    /**
     * Gets the array contents of the YML files inside the given directory,
     * optionally only from files matching the given name pattern.
     *
     * @param string $directory
     * @param string $namePattern The name of the file to load, without .yml extension. Can include directories.
     * @return array
     */
    public function loadData($directory, $namePattern = '*.yml')
    {
        $files = $this->findFiles($directory, $namePattern);

        $yml = [];
        foreach ($files as $f) {
            $yml += Yaml::parse($f->getContents());
        }

        return $yml;
    }
}
