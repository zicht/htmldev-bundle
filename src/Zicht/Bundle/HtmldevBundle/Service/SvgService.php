<?php
/**
 * @author Robert van Kempen <robert@zicht.nl>
 * @copyright Zicht online
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Class that turns Sass file into a readable palette that
 * can be displayed in a style guide.
 *
 * @package Zicht\Bundle\HtmldevBundle\Service
 */
class SvgService implements SvgServiceInterface
{
    /**
     * @var string
     */
    private $htmldevDirectory;


    /**
     * Initializes a new instance of the SvgService.
     *
     * @param string $htmldevDirectory
     */
    public function __construct($htmldevDirectory)
    {
        $this->htmldevDirectory = $htmldevDirectory;
    }


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
     * @param string $directory
     *
     * @return null|string
     */
    public function getSvgIcon($symbol, $width, $height, $viewboxX, $viewboxY, $extraCssClasses, $includeAccessibility, $directory)
    {
        $cssClasses = array_merge(['c-icon', sprintf('c-icon--%s', $symbol)], $extraCssClasses);
        $accessibilityAttributes = $includeAccessibility ? ['aria-hidden' => 'true', 'role' => 'img'] : [];

        return $this->getSvg(
            $symbol,
            $width,
            $height,
            $viewboxX,
            $viewboxY,
            $cssClasses,
            $accessibilityAttributes,
            $directory
        );
    }


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
     * @param string $directory
     *
     * @return null|string
     */
    public function getSvg($name, $width, $height, $viewboxX, $viewboxY, $cssClasses, $accessibilityAttributes, $directory)
    {
        $fileName = sprintf('%s/%s/%s.svg', $this->htmldevDirectory, $directory, $name);
        if (!is_file($fileName)) {
            return null;
        }

        $d = new \DOMDocument();
        $d->load($fileName);

        $svg = $d->getElementsByTagName('svg')->item(0);

        /**
         * Set dimensions to ensure proper rendering across different browsers.
         */
        if (!empty($width)) {
            $this->setSvgAttribute($svg, 'width', $this->addUnit($width, 'px'), true);
        }

        if (!empty($height)) {
            $this->setSvgAttribute($svg, 'height', $this->addUnit($height, 'px'), true);
        }

        if (!empty($viewboxX) && !empty($viewboxY)) {
            $this->setSvgAttribute($svg, 'viewbox', sprintf('0 0 %s %s', $this->removeUnit($viewboxX, 'px'), $this->removeUnit($viewboxY, 'px')), true);
        }

        $this->setSvgAttribute($svg, 'preserveAspectRatio', 'xMidYMid meet');

        /**
         * Add CSS classes for extra theming or positioning.
         */
        if (is_array($cssClasses)) {
            $this->setSvgAttribute($svg, 'class',  implode('  ', $cssClasses));
        }

        /**
         * Set accessibility attributes.
         */
        if (is_array($accessibilityAttributes)) {
            foreach ($accessibilityAttributes as $attribute => $value) {
                $svg->setAttribute($attribute, $value);
            }
        }

        return $d->saveXML();
    }


    /**
     * Adds the given name-value pair as an XML attribute to the given SVG node, but
     * does not override existing attribute values, except when the merge parameter
     * is set to `true`.
     *
     * @param \DOMNode $svg
     * @param string $name
     * @param string
     * @param boolean $overwrite
     */
    public function setSvgAttribute($svg, $name, $value, $overwrite = false)
    {
        $existingValue = $svg->getAttribute($name);

        if (!$overwrite && $existingValue !== '') {
            return;
        }

        if ($overwrite && $existingValue !== '') {
            $svg->setAttribute($name, $value);

            return;
        }

        $svg->setAttribute($name, $value);
    }


    /**
     * Adds the given unit to the given value if it's not present yet.
     *
     * @param string $value
     * @param string $unit
     *
     * @return string
     */
    public function addUnit($value, $unit)
    {
        if (strpos($value, $unit) > 0) {
            return $value;
        }

        return sprintf('%s%s', $value, $unit);
    }


    /**
     * Removes the given unit from the given value, if it's not already removed.
     *
     * @param string $value
     * @param string $unit
     *
     * @return string
     */
    public function removeUnit($value, $unit)
    {
        if (strpos($value, $unit) === false) {
            return $value;
        }

        return str_replace($unit, '', $value);
    }
}