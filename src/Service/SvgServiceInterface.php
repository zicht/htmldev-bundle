<?php declare(strict_types=1);
/**
 * @copyright Zicht Online <https://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Provides a contract for services that load SVG files.
 */
interface SvgServiceInterface
{
    /**
     * Loads the given SVG file name from disk and decorates it with the given attributes.
     *
     * @param string $name
     * @param string $width
     * @param string $height
     * @param string $viewboxX
     * @param string $viewboxY
     * @param array $cssClasses
     * @param array $attributes
     * @param string $title
     * @param string $directory
     * @return string|null
     */
    public function getSvg($name, $width, $height, $viewboxX, $viewboxY, $cssClasses, $attributes, $title, $directory);
}
