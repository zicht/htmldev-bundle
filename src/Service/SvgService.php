<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */
namespace Zicht\Bundle\HtmldevBundle\Service;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Class that reads the contents of an SVG file.
 */
class SvgService implements SvgServiceInterface
{
    /** @var string */
    private $baseDir;
    /** @var LoggerInterface */
    private $logger;
    /** @var CacheInterface */
    private $cache;

    /**
     * @param string $baseDir
     * @param CacheInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(string $baseDir, CacheInterface $cache, LoggerInterface $logger)
    {
        $this->baseDir = $baseDir;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function getSvg($name, $width, $height, $viewboxX, $viewboxY, $cssClasses, $attributes, $title, $directory)
    {
        $key = sha1(json_encode(func_get_args()));
        if (null === $out = $this->cache->get($key)) {
            $fileName = sprintf('%s/%s/%s.svg', $this->baseDir, $directory, $name);
            if (!is_file($fileName)) {
                $this->logger->info('svg file not found: ' . $fileName);
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
                $this->setSvgAttribute($svg, 'viewBox', sprintf('0 0 %s %s', $this->removeUnit($viewboxX, 'px'), $this->removeUnit($viewboxY, 'px')), true);
            }
            $this->setSvgAttribute($svg, 'preserveAspectRatio', 'xMidYMid meet');
            /**
             * Add CSS classes for extra theming or positioning.
             */
            if (is_array($cssClasses)) {
                $this->setSvgAttribute($svg, 'class',  implode('  ', $cssClasses));
            }
            if (!empty($title)) {
                $this->logger->debug(sprintf('adding title to svg: "%s"', $title));
                $svg->appendChild($d->createElement('title', $title));
            }
            /**
             * Set accessibility attributes.
             */
            if (is_array($attributes)) {
                foreach ($attributes as $attr => $value) {
                    $this->logger->debug(sprintf('set attribute %s to value %s', $name, $value));
                    $svg->setAttribute($attr, $value);
                }
            }
            $out = $d->saveXML();
            $this->cache->set($key, $out);
        } else {
            $this->logger->info('svg ' . $name . ' loaded from cache');
        }
        return $out;
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
    public function setSvgAttribute(\DOMElement $svg, $name, $value, $overwrite = false)
    {
        $existingValue = $svg->getAttribute($name);
        if (!$overwrite && $existingValue !== '') {
            $this->logger->debug(sprintf('not set attribute %s because value is set ', $name));
            return;
        }
        if ($overwrite && $existingValue !== '') {
            $this->logger->debug(sprintf('overwrite attribute %s to value %s', $name, $value));
            $svg->setAttribute($name, $value);
            return;
        }
        $this->logger->debug(sprintf('set attribute %s to value %s', $name, $value));
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
