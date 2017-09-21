<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Twig;

use Twig_Extension;
use Symfony\Component\Yaml\Yaml;
use Zicht\Bundle\HtmldevBundle\Service\ColorServiceInterface;
use Zicht\Bundle\HtmldevBundle\Service\SvgServiceInterface;

/**
 * Twig extensions that make rendering a style guide easier.
 */
class HtmldevExtension extends Twig_Extension
{
    /**
     * @var string
     */
    private $htmldevDirectory;

    /**
     * @var ColorServiceInterface
     */
    private $colorService;

    /**
     * @var SvgServiceInterface
     */
    private $svgService;

    /**
     * Initializes a new instance of the HtmldevExtension class.
     *
     * @param string $htmldevDirectory
     * @param ColorServiceInterface $colorService
     * @param SvgServiceInterface $svgService
     */
    public function __construct(
        $htmldevDirectory,
        ColorServiceInterface $colorService,
        SvgServiceInterface $svgService)
    {
        $this->htmldevDirectory = $htmldevDirectory;
        $this->colorService = $colorService;
        $this->svgService = $svgService;
    }

    /**
     * @{inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('load_data', [$this, 'loadData']),
            new \Twig_SimpleFunction('icons', [$this, 'getIcons']),
            new \Twig_SimpleFunction('color_groups', [$this->colorService, 'getColorGroups']),
            new \Twig_SimpleFunction('luminance', [$this->colorService, 'getLuminance']),
            new \Twig_SimpleFunction('embed_svg', [$this->svgService, 'getSvg']),
            new \Twig_SimpleFunction('embed_icon', [$this->svgService, 'getSvgIcon'])
        ];
    }

    /**
     * Load data from yml file.
     *
     * @param string $type
     * @return array
     */
    public function loadData($type)
    {
        $fileName = sprintf('%s/data/%s.yml', $this->htmldevDirectory, $type);
        if (!is_file($fileName)) {
            return [];
        }

        return Yaml::parse(file_get_contents($fileName));
    }

    /**
     * Returns the names of the icons of the supplied type, without file extension.
     *
     * @param string $type
     * @return array
     */
    public function getIcons($type)
    {
        $imageDirectory = sprintf('%s/images/icons/%s', $this->htmldevDirectory, $type);
        if (!is_dir($imageDirectory)) {
            return [];
        }

        $files = array_diff(scandir($imageDirectory), ['..', '.']);
        $iconNames = array_map(function($item) {
            return basename($item, '.svg');
        }, $files);

        return $iconNames;
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
