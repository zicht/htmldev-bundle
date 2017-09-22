<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */
namespace Zicht\Bundle\HtmldevBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Zicht\Bundle\HtmldevBundle\Service\ColorServiceInterface;

/**
 * Twig extension for using colors in the styleguide.
 */
class ColorExtension extends Twig_Extension
{
    /** @var ColorServiceInterface */
    private $colorService;

    /**
     * Initializes a new instance of the ColorExtension class.
     *
     * @param ColorServiceInterface $colorService
     */
    public function __construct(ColorServiceInterface $colorService)
    {
        $this->colorService = $colorService;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('color_palette', [$this->colorService, 'getColorGroups'])
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getTests()
    {
        return [
            new \Twig_SimpleTest('dark', [$this, 'isDark']),
            new \Twig_SimpleTest('light', [$this, 'isLight'])
        ];
    }

    /**
     * @param string $hexColor A CSS hex color, can be formatted as `#888`, `#888888`, `888` or `888888`.
     * @return bool
     */
    public function isDark($hexColor)
    {
        return !$this->isLight($hexColor);
    }

    /**
     * @param string $hexColor A CSS hex color, can be formatted as `#888`, `#888888`, `888` or `888888`.
     * @return bool
     */
    public function isLight($hexColor)
    {
        return $this->getLuminance($hexColor) > 500;
    }

    /**
     * @param string $hexColor A CSS hex color, can be formatted as `#888`, `#888888`, `888` or `888888`.
     *
     * @return number
     */
    protected function getLuminance($hexColor)
    {
        $hexColor = trim(str_replace('#', '', $hexColor));

        if (strlen($hexColor) === 3) {
            $hexColor .= $hexColor;
        }

        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        return $r + $g + $b;
    }

    /**
     * The name of this twig extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'htmldev_color_twig_extension';
    }
}
