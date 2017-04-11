<?php
/**
 * @author Robert van Kempen <robert@zicht.nl>
 * @author Boudewijn Schoon <boudewijn@zicht.nl>
 * @copyright Zicht online
 */

namespace Zicht\Bundle\HtmldevBundle\Twig;

use Twig_Extension;
use Symfony\Component\Yaml\Yaml;
use Zicht\Bundle\HtmldevBundle\Service\HtmlService;
use Zicht\Bundle\HtmldevBundle\Service\HtmlServiceInterface;
use Zicht\Bundle\HtmldevBundle\Service\IColorService;
use Zicht\Bundle\HtmldevBundle\Service\SvgServiceInterface;

/**
 * Twig extensions that make rendering a style guide easier.
 *
 * @package Zicht\Bundle\HtmldevBundle\Twig
 */
class HtmldevExtension extends Twig_Extension
{
    /**
     * @var string
     */
    private $htmldevDirectory;

    /**
     * @var IColorService
     */
    private $colorService;

    /**
     * @var SvgServiceInterface
     */
    private $svgService;

    /**
     * @var HtmlServiceInterface
     */
    private $htmlService;


    /**
     * Initializes a new instance of the HtmldevExtension class.
     *
     * @param string $htmldevDirectory
     * @param IColorService $colorService
     * @param SvgServiceInterface $svgService
     */
    public function __construct(
        $htmldevDirectory,
        IColorService $colorService,
        SvgServiceInterface $svgService,
        HtmlServiceInterface $htmlService)
    {
        $this->htmldevDirectory = $htmldevDirectory;
        $this->colorService = $colorService;
        $this->svgService = $svgService;
        $this->htmlService = $htmlService;
    }


    /**
     * @{inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('ui_and_html', array($this, 'ui_and_html'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('get_ui_and_html', array($this, 'get_ui_and_html'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('get_current_datetime', array($this, 'get_current_datetime')),
            new \Twig_SimpleFunction('load_data', array($this, 'loadData')),
            new \Twig_SimpleFunction('icons', array($this, 'getIcons')),
            new \Twig_SimpleFunction('color_groups', array($this->colorService, 'getColorGroups')),
            new \Twig_SimpleFunction('luminance', array($this->colorService, 'getLuminance')),
            new \Twig_SimpleFunction('embed_svg', array($this->svgService, 'getSvg')),
            new \Twig_SimpleFunction('embed_icon', array($this->svgService, 'getSvgIcon')),
            new \Twig_SimpleFunction('classes', array($this->htmlService, 'getClasses'))
        );
    }


    /**
     * @{inheritDoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('ui_printable_arguments', [$this, 'ui_printable_arguments']),
        ];
    }


    /**
     * Format a set of arguments into a legible json object.
     *
     * @param mixed $val
     * @param bool $format
     * @return array|string
     */
    // @codingStandardsIgnoreStart
    public function ui_printable_arguments($val, $format = true)
    // @codingStandardsIgnoreEnd
    {
        if ($val instanceof \Twig_Markup) {
            $val = (string)$val;
        }

        if (is_array($val)) {
            foreach ($val as $key => $value) {
                $val[$key] = $this->ui_printable_arguments($value, false);
            }
        } else if (is_object($val)) {
            foreach (get_object_vars($val) as $key => $value) {
                $val->$key = $this->ui_printable_arguments($value, false);
            }
        } else if (is_string($val)) {
            $val = trim($val);
            if (strlen($val) >  50) {
                $val = substr($val, 0, 47) . '...';
            }
        }

        if ($format) {
            return json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        return $val;
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
    // @codingStandardsIgnoreStart
    public function get_current_datetime($modify = '')
    // @codingStandardsIgnoreEnd
    {
        // use static $now to ensure that all dates are -exactly- the same
        static $now = null;

        if (null === $now) {
            $now = new \DateTime('now');
        }

        $datetime = clone $now;
        if (!empty($modify)) {
            $datetime->modify($modify);
        }

        return $datetime;
    }

    /**
     * Renders ui and html
     *
     * @param string $html
     * @return string
     *
     * @deprecated Use get_ui_and_html instead
     */
    // @codingStandardsIgnoreStart
    public function ui_and_html($html)
    // @codingStandardsIgnoreEnd
    {
        return $this->get_ui_and_html($html);
    }

    /**
     * Renders the supplied HTML both as actual HTML and a code block.
     *
     * @param string $html
     * @return string
     */
    // @codingStandardsIgnoreStart
    public function get_ui_and_html($html)
    // @codingStandardsIgnoreEnd
    {
        $resultHtml = sprintf('%s
            <pre>
                <code>%s</code>
            </pre>
        ', $html, htmlentities($html));

        return $resultHtml;
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
