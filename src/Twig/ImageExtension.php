<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */
namespace Zicht\Bundle\HtmldevBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Markup as TwigMarkup;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\Yaml\Yaml;
use Zicht\Bundle\HtmldevBundle\Service\ColorServiceInterface;
use Zicht\Bundle\HtmldevBundle\Service\SvgServiceInterface;

/**
 * Twig extension for handling images.
 */
class ImageExtension extends AbstractExtension
{
    /** @var string */
    private $imageDirectory;

    /** @var SvgServiceInterface */
    private $svgService;

    /**
     * @param string $imageDirectory
     * @param SvgServiceInterface $svgService
     */
    public function __construct($imageDirectory, SvgServiceInterface $svgService)
    {
        $this->imageDirectory = $imageDirectory;
        $this->svgService = $svgService;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('icon_list', [$this, 'getIcons']),
            new TwigFunction('embed_svg', [$this->svgService, 'getSvg']),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('inline_images', [$this, 'inlineImagesInHtmlBlock']),
        ];
    }

    public function inlineImagesInHtmlBlock(TwigMarkup $block): TwigMarkup
    {
        $hasMatches = preg_match_all('/<img(?:\s+(?P<attrs>.*?))?\\/?>/is', $block, $imageMatches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
        if (false === $hasMatches || 0 === $hasMatches) {
            return $block;
        }

        // This array of options will be passed as arguments to the image processor method and therefor the elements must match this method's arguments
        $defaultOptions = [
            'src' => '',
            'width' => '',
            'height' => '',
            'viewbox-x' => '',
            'viewbox-y' => '',
            'class' => [],
            'attributes' => [
                'aria-hidden' => 'true',
                'role' => 'img',
            ],
            'title' => '',
            'directory' => null,
        ];

        $newBlock = null;
        $images = [];
        $blockPosModifier = 0;
        foreach ($imageMatches as $imageMatch) {
            if (!isset($imageMatch['attrs']) || '' === $imageMatch['attrs'][0]) {
                continue;
            }

            preg_match_all('/(?P<attr>[a-z_:\\-]+)\s*=\s*([\'"])(?P<value>.*?)(\\2)/i', $imageMatch['attrs'][0], $attrMatches, PREG_SET_ORDER);
            $attrs = $defaultOptions;
            foreach ($attrMatches as $attrMatch) {
                $attr = str_replace('_', '-', strtolower($attrMatch['attr']));
                $value = html_entity_decode($attrMatch['value']);
                if (!array_key_exists($attr, $attrs)) {
                    $attrs['attributes'][$attr] = $value;
                } elseif (is_array($attrs[$attr])) {
                    $attrs[$attr][] = $value;
                } else {
                    $attrs[$attr] = $value;
                }
            }
            if ('' === $attrs['src']) {
                continue;
            }

            if (false !== ($qMarkPos = strpos($attrs['src'], '?'))) {
                $attrs['src'] = substr($attrs['src'], 0, $qMarkPos);
            }
            if (false !== strpos($attrs['src'], '/')) {
                $attrs['directory'] = dirname($attrs['src']);
                $attrs['src'] = basename($attrs['src']);
            }

            if (false === strrpos($attrs['src'], '.')) {
                throw new \InvalidArgumentException(sprintf('No file extension was found for the image "%s/%s"', $attrs['directory'], $attrs['src']));
            }

            $inlineHtml = null;
            $ext = strtolower(substr($attrs['src'], strrpos($attrs['src'], '.') + 1));
            switch ($ext) {
                case 'svg':
                    $attrs['src'] = substr($attrs['src'], 0, -4);
                    $inlineHtml = $this->svgService->getSvg(...array_values($attrs));
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf('File type .%s of the image "%s/%s" is currently not supported', $ext, $attrs['directory'], $attrs['src']));
            }

            if (!$inlineHtml) {
                continue;
            }

            // $imageMatch element 0 is the full match. And then element 1 contains the position because of the PREG_OFFSET_CAPTURE flag
            // + Correct the position with the number of size changes so far in $blockPosModifier
            $oldImageHtmlStartPos = $imageMatch[0][1] + $blockPosModifier;
            // First element is the full match. And then element 0 contains the actual match string
            $oldImageHtmlLength = strlen($imageMatch[0][0]);
            $newBlock = substr_replace(($newBlock !== null ? $newBlock : $block), $inlineHtml, $oldImageHtmlStartPos, $oldImageHtmlLength);

            $blockPosModifier += strlen($inlineHtml) - $oldImageHtmlLength;
        }

        return ($newBlock !== null ? new TwigMarkup($newBlock, 'UTF-8') : $block);
    }

    /**
     * Returns the names of the icons of the supplied type, without file extension.
     *
     * @param string $type
     * @return array
     */
    public function getIcons($type = '')
    {
        $imageDirectory = sprintf('%s/%s', rtrim($this->imageDirectory, '/'), $type);
        if (!is_dir($imageDirectory)) {
            return [];
        }

        $files = array_diff(scandir($imageDirectory), ['..', '.']);
        return array_map(
            static function ($item) {
                return basename($item, '.svg');
            },
            $files
        );
    }

    /**
     * The name of this twig extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'htmldev_twig_image_extension';
    }
}
