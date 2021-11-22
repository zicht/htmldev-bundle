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

        if (0 === preg_match_all('/\$(?P<name>zss--colors(?:[-_][a-z0-9\-_]+)?):\s*(?P<value>[^;]+)\s*;/', $css, $colorVariables, PREG_SET_ORDER)) {
            return [];
        }

        $groups = [];
        foreach ($colorVariables as $variable) {
            $colors = $this->getColorsFromSassVariable($variable['value']);
            $groups[$variable['name']] = array_key_exists($variable['name'], $groups)
                ? array_merge($groups[$variable['name']], $colors)
                : $colors;
            ksort($groups[$variable['name']], SORT_NATURAL);
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

        if (0 === preg_match_all('/([a-z0-9\-]+):[\t ]*([#0-9a-f]+|rgba?\([0-9\., ]+\))/', $clean, $pairs, PREG_SET_ORDER)) {
            return [];
        }

        $colors = [];
        foreach ($pairs as $pair) {
            $colors[$pair[1]] = $pair[2];
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
        return preg_replace(['!/\*.*\*/!', '/\$[A-Za-z][A-Za-z0-9\-]*/'], '', $sassMap);
    }
}
