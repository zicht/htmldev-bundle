<?php
/**
 * @copyright Zicht Online
 */
namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Class that turns a Sass file with ZSS color definitions into a readable palette
 * that can be displayed in the styleguide.
 */
class ZssColorService implements ColorServiceInterface
{
    /** @var DataLoaderInterface */
    private $textLoader;

    /**
     * Initializes a new instance of the ZssColorService class.
     *
     * @param DataLoaderInterface $textLoader
     */
    public function __construct(DataLoaderInterface $textLoader)
    {
        $this->textLoader = $textLoader;
    }

    /**
     * {@inheritDoc}
     */
    public function getColorGroups($file)
    {
        $css = $this->textLoader->loadData('sass/variables', $file);
        $css = preg_replace('/\s+/', '', $css);
        $colorVariables = array_filter(
            explode('$', $css),
            function ($value, $key) {
            return strpos($value, 'zss--colors:') === 0;
            },
            ARRAY_FILTER_USE_BOTH
        );

        $groups = [];
        foreach ($colorVariables as $variable) {
            $groups[substr($variable, 0, strpos($variable, ':'))] = $this->getColorsFromSassVariable($variable);
        }

        return $groups;
    }

    /**
     * @param string $sassVariable
     * @return array
     */
    protected function getColorsFromSassVariable($sassVariable)
    {
        $clean = $this->cleanSassMap($sassVariable);

        preg_match_all('/([a-z0-9\-]+):([#0-9a-f]+|[rgba0-9\.\(\),]+)/', $clean, $pairs);
        if (count($pairs) < 3) {
            return [];
        }

        $colors = [];
        foreach ($pairs[1] as $key => $name) {
            $colors[$name] = $pairs[2][$key];
        }

        return $colors;
    }

    /**
     * Clean the given variable from comments that get in the way of the regex.
     *
     * @param string $sassMap
     * @return string
     */
    protected function cleanSassMap($sassMap)
    {
        $clean = str_replace('zss--colors:(', '', $sassMap);
        $clean = preg_replace('/\/\*stylelint-[a-z\*\-\/]+/', '', $clean);
        $clean = preg_replace('/,?\);/', '', $clean);

        return $clean;
    }
}
