<?php
/**
 * @copyright Zicht Online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Class Template
 */
class Template
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * Construct with the passed dir as the root for the template files
     *
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * Returns an iterator of all template files
     *
     * @return \SplFileInfo[]
     */
    public function findAll()
    {
        return new \RegexIterator(new \DirectoryIterator($this->rootDir), '/^[^\_].*?\.html\.twig$/', \RegexIterator::MATCH);
    }

    /**
     * Returns the twig template represented by the specified file name
     *
     * @param string $name
     * @return string
     */
    public function find($name)
    {
        return sprintf('%s.twig', $name);
    }
}
