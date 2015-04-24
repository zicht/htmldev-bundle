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
     * @param string $rootDir
     */
    function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @return \RegexIterator
     */
    public function findAll()
    {
        return new \RegexIterator(new \DirectoryIterator($this->rootDir), '/^[^\_].*?\.html\.twig$/', \RegexIterator::MATCH);
    }

    /**
     * @param string $name
     * @return string
     */
    public function find($name)
    {
        return sprintf('%s.twig', $name);
    }
}