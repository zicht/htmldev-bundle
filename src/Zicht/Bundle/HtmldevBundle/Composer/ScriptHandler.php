<?php
namespace Zicht\Bundle\HtmldevBundle\Controller;

use \Composer\Script\CommandEvent;

/**
 * Class ScriptHandler
 *
 * @package Zicht\Bundle\HtmldevBundle\Controller
 */
class ScriptHandler
{
    /**
     * @param CommandEvent $event
     */
    public static function createHtmldev(CommandEvent $event)
    {
        $root = getcwd();

        mkdir(sprintf('%s/htmldev', $root), 0755);
        mkdir(sprintf('%s/htmldev/images', $root), 0755);
        mkdir(sprintf('%s/htmldev/javascript', $root), 0755);
        mkdir(sprintf('%s/htmldev/sass', $root), 0755);
        mkdir(sprintf('%s/htmldev/style', $root), 0755);
        copy(sprintf('%s/vendor/zicht/htmldev-bundle/src/Zicht/Bundle/HtmldevBundle/Resources/.bowerrc', $root), sprintf('%s/htmldev/.bowerrc', $root));
        copy(sprintf('%s/vendor/zicht/htmldev-bundle/src/Zicht/Bundle/HtmldevBundle/Resources/views/_index.html.twig', $root), sprintf('%s/htmldev/_index.html.twig', $root));
    }

    /**
     * @param CommandEvent $event
     * @return array
     */
    protected static function getOptions(CommandEvent $event)
    {
        return array();
    }
}