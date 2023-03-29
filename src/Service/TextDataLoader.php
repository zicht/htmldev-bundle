<?php declare(strict_types=1);
/**
 * @copyright Zicht Online <http://zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Loads text data from a directory in the styleguide.
 */
class TextDataLoader extends AbstractDataLoader implements DataLoaderInterface
{
    /**
     * Gets the text contents of the file inside the given directory,
     * optionally only from files matching the given name pattern.
     *
     * @param string $directory
     * @param string $namePattern
     * @return string
     */
    public function loadData($directory, $namePattern = '*.txt')
    {
        $files = $this->findFiles($directory, $namePattern);

        $text = '';
        foreach ($files as $f) {
            $text .= $f->getContents();
        }

        return $text;
    }
}
