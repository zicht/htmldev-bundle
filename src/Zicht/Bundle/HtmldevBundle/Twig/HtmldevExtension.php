<?php

namespace Zicht\Bundle\HtmldevBundle\Twig;

use \Twig_Extension;

/**
 * Twig extensions that make rendering a style guide easier.
 *
 * @package Zicht\Bundle\HtmldevBundle\Twig
 */
class HtmldevExtension extends Twig_Extension
{
    /**
     * Gets the list of functions available in the Twig templates.
     *
     * @return array
     */
    function getFunctions() {
        return array(
            new \Twig_SimpleFunction('ui_and_html', array($this, 'ui_and_html'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('get_ui_and_html', array($this, 'get_ui_and_html'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('get_current_datetime', array($this, 'get_current_datetime')),
        );
    }

    /**
     * Returns the current time with, optionally, a modifier
     *
     * > get_current_datetime()
     * Datetime('now')
     *
     * > get_current_datetime('+1 day')
     * Datetime('now')->modify('+1 day')
     *
     * @param string $modify
     * @return \DateTime
     */
    public function get_current_datetime($modify = '')
    {
        // use static $now to ensure that all dates are -exactly- the same
        static $now = null;
        if (null === $now) { $now = new \DateTime('now'); }

        $datetime = clone $now;
        if (!empty($modify)) {
            $datetime->modify($modify);
        }

        return $datetime;
    }

    /**
     * @param $html
     * @deprecated Use get_ui_and_html instead
     */
    public function ui_and_html($html)
    {
        return $this->get_ui_and_html($html);
    }

    /**
     * Renders the supplied HTML both as actual HTML and a code block.
     *
     * @param $html
     * @return string
     */
    public function get_ui_and_html($html)
    {
        $resultHtml = sprintf('%s
            <pre>
                <code>%s</code>
            </pre>
        ', $html, htmlentities($html));

        return $resultHtml;
    }

    /**
     * Register a 'faker' global if faker is available
     *
     * @return array
     */
    public function getGlobals()
    {
        if (class_exists('Faker\Factory')) {
            return [
                'faker' => \Faker\Factory::create()
            ];
        }
        return [];
    }

    /**
     * The name of this twig extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'htmldev_twig_extension';
    }
}
