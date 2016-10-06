<?php
/**
 * @author Robert van Kempen <robert@zicht.nl>
 * @copyright Zicht online
 */

namespace Zicht\Bundle\HtmldevBundle\Service;

/**
 * Service for manipulating HTML.
 *
 * @package Zicht\Bundle\HtmldevBundle\Service
 */
class HtmlService implements HtmlServiceInterface
{
    /**
     * Builds a string of CSS class names based on supplied predicates.
     *
     * @param array ...$config
     *
     * @return string
     */
    public function getClasses(...$config)
    {
        $classes = [];

        foreach ($config as $argument) {
            $classes = $this->addArgumentToClasses($classes, $argument);
        }

        return join('  ', $classes);
    }


    /**
     * @param $classes
     * @param $argument
     * @return array
     */
    private function addArgumentToClasses($classes, $argument)
    {
        switch(gettype($argument))
        {
            case 'string':
                array_push($classes, $argument);
                break;
            case 'array':
                if (!$this->hasStringKeys($argument)) {
                    $classes = array_merge($classes, $argument);
                    break;
                }

                foreach($argument as $class => $predicate) {
                    if (!$predicate) {
                        continue;
                    }

                    array_push($classes, $class);
                }
                break;

            default:
                break;
        }

        return $classes;
    }


    /**
     * Determines whether an array has string keys.
     *
     * @param array $array
     * @return bool
     */
    private function hasStringKeys(array $array)
    {
        $keys = array_keys($array);

        /**
         * If the array keys of the keys match the keys, then the array must not be associative
         */
        return array_keys($keys) !== $keys;
    }
}