<?php
/**
 * @author Robert van Kempen <robert@zicht.nl>
 * @copyright Zicht online
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Provides a contract for services that load SVG files.
 *
 * @package Zicht\Bundle\HtmldevBundle\Service
 */
interface SvgServiceInterface
{
    /**
     * Loads the given SVG symbol from disk and decorates it with the appropriate icon attributes.
     *
     * @param string $symbol
     * @param string $width
     * @param string $height
     * @param string $viewboxX
     * @param string $viewboxY
     * @param array $extraCssClasses
     * @param array $includeAccessibility
     * @param string $title
     * @param string $directory
     *
     * @return null|string
     */
    public function getSvgIcon($symbol, $width, $height, $viewboxX, $viewboxY, $extraCssClasses, $includeAccessibility, $title, $directory);


    /**
     * Loads the given SVG file name from disk and decorates it with the given attributes.
     *
     * @param string $name
     * @param string $width
     * @param string $height
     * @param string $viewboxX
     * @param string $viewboxY
     * @param array $cssClasses
     * @param array $accessibilityAttributes
     * @param string $title
     * @param string $directory
     *
     * @return null|string
     */
    public function getSvg($name, $width, $height, $viewboxX, $viewboxY, $cssClasses, $accessibilityAttributes, $title, $directory);
}