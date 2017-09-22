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
    /** @var string */
    private $htmldevDirectory;

    /**
     * Initializes a new instance of the AbstractDataLoader class.
     *
     * @param string $htmldevDirectory
     */
    public function __construct($htmldevDirectory)
    {
        $this->htmldevDirectory = $htmldevDirectory;
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
            ->in(sprintf('%s/%s', $this->htmldevDirectory, $directory))
            ->name($namePattern);
    }
}
