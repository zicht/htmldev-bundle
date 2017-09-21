<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Twig;

use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Twig extensions for manipulating data.
 */
class UtilExtension extends Twig_Extension
{
    /**
     * @{inheritDoc}
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('delete', [$this, 'deleteFromArray']),
            new Twig_SimpleFilter('ui_printable_arguments', [$this, 'getUiPrintableArguments'])
        ];
    }

    /**
     * Removes the given keys from the given array.
     *
     * @param array $array
     * @param array $keysToDelete
     *
     * @return array
     */
    public function deleteFromArray($array, ...$keysToDelete)
    {
        if (!is_array($array)) {
            throw new \InvalidArgumentException(sprintf('Cannot delete from an object that is not an array. Given type: %s', gettype($array)));
        }

        return array_filter($array, function($value, $key) use ($keysToDelete) {
            return !in_array($key, $keysToDelete);
        }, ARRAY_FILTER_USE_BOTH);
    }


    /**
     * Format a set of arguments into a legible json object.
     *
     * @param mixed $val
     * @param bool $format
     * @return array|string
     */
    public function getUiPrintableArguments($val, $format = true)
    {
        if ($val instanceof \Twig_Markup) {
            $val = (string)$val;
        }

        if (is_array($val)) {
            foreach ($val as $key => $value) {
                $val[$key] = $this->getUiPrintableArguments($value, false);
            }
        } else if (is_object($val)) {
            foreach (get_object_vars($val) as $key => $value) {
                $val->$key = $this->getUiPrintableArguments($value, false);
            }
        } else if (is_string($val)) {
            $val = trim($val);
        }

        if ($format) {
            return $this->formatJsonStringToTwigObjectString(
                json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
        }

        return $val;
    }

    /**
     * @param $json
     *
     * @return string
     */
    protected function formatJsonStringToTwigObjectString($json)
    {
        $s = str_replace('"', '\'', $json);

        return preg_replace('/\'(\w+)\'/i', '${1}', $s);
    }

    /**
     * The name of this twig extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'htmldev_twig_util_extension';
    }
}