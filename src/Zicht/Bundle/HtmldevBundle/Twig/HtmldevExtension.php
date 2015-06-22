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
            'ui_and_html' => new \Twig_Function_Method($this, 'getUiAndHtml', array('is_safe' => array('html')))
        );
    }

    /**
     * Renders the supplied HTML both as actual HTML and a code block.
     *
     * @param $html
     * @return string
     */
    public function getUiAndHtml($html)
    {
        $resultHtml = sprintf('%s
            <pre>
                <code>%s</code>
            </pre>
        ', $html, htmlentities($html));

        return $resultHtml;
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