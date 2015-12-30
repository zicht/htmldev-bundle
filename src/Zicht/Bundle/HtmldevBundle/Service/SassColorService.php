<?php

namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Class that turns Sass file into a readable palette that
 * can be displayed in a style guide.
 *
 * @package Zicht\Bundle\HtmldevBundle\Service
 */
class SassColorService implements IColorService
{
    /**
     * @var string
     */
    private $htmldevDirectory;

    /**
     * Initializes a new instance of the SassColorService.
     *
     * @param $htmldevDirectory
     */
    public function __construct($htmldevDirectory)
    {
        $this->htmldevDirectory = $htmldevDirectory;
    }

    /**
     * @param string $hexColor A CSS hex color, can be formatted as `#888`, `#888888`, `888` or `888888`.
     *
     * @return number
     */
    public function getLuminance($hexColor)
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
     * Looks in the supplied Sass file for sections and corresponding colors,
     * and turns them into an array that can be used to display the entire palette.
     *
     * @param string $file The location and name of the file, relative to the htmldev directory.
     *
     * @return array
     */
    public function getColorGroups($file)
    {
        $fileName = sprintf('%s/%s', $this->htmldevDirectory, $file);
        if (!is_file($fileName)) {
            throw new \InvalidArgumentException('Supplied argument `file` is not a valid file location.');
        }

        $css = file_get_contents($fileName);
        $cssLines = array_filter(explode('//', $css), function($item) {
            return !empty(trim($item));
        });

        $groups = [];
        $counter = 0;
        $isAGroup = false;
        $sectionMarker = '@section';

        foreach ($cssLines as $l) {
            if (empty(trim($l))) {
                continue;
            }

            if (strpos($l, $sectionMarker)) {
                $groups[$counter]['title'] = trim(str_replace($sectionMarker, '', $l));
                $isAGroup = true;

                continue;
            }

            if ($isAGroup) {
                $colorPairs = $this->createPairsFromColorLines(explode(PHP_EOL, $l));
                $groups[$counter]['pairs'] = $colorPairs;

                $isAGroup = false;
                $counter += 1;
            }
        }

        return $groups;
    }

    /**
     * Helper function that reads color definitions in Sass and turns
     * them into an array.
     *
     * @param $colorLines
     *
     * @return array
     */
    protected function createPairsFromColorLines($colorLines)
    {
        $pairs = [];

        foreach($colorLines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $pair = explode(':', trim(str_replace(array('!default', ';'), '', $line)));
            array_push($pairs, $pair);
        }

        return $pairs;
    }
}