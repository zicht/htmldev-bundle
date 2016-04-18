<?php
/**
 * @copyright Zicht online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Class Template
 *
 * @package Zicht\Bundle\HtmldevBundle\Service
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