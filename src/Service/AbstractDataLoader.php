<?php
/**
 * @copyright Zicht Online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads text data from a directory in the styleguide.
 */
abstract class AbstractDataLoader implements DataLoaderInterface
{
    /** @var string[] */
    private $paths;

    /**
     * @param array $paths
     */
    public function __construct($paths)
    {
        $this->paths = $paths;
    }

    /**
     * Finds files in the given directory that match the given name pattern.
     *
     * @param string $directory
     * @param string $namePattern
     * @return Finder
     */
    protected function findFiles($directory, $namePattern)
    {
        $finder = new Finder();

        return $finder
            ->in($this->findPath($directory))
            ->name($namePattern);
    }

    /**
     * @param string $directory
     * @return string
     */
    protected function findPath($directory)
    {
        if (strpos($directory, '/') === false) {
            return $this->paths[$directory] ?? $directory;
        }

        $dirParts = explode('/', $directory);
        do {
            $tryPathKey = implode('_', $dirParts);
            if (isset($this->paths[$tryPathKey])) {
                return $this->paths[$tryPathKey];
            }
        } while (array_pop($dirParts));

        return $directory;
    }
}
