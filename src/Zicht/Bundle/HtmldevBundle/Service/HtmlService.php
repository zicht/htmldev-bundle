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
     * Builds a string of class names based on supplied predicates.
     *
     * @param array ...$config
     *
     * @return string
     */
    public function getClasses(...$config)
    {
        $classes = [];

        foreach ($config as $argument) {
            switch(gettype($argument))
            {
                case 'string':
                    array_push($classes, $argument);
                    break;
                case 'array':
                    if (!$this->hasStringKeys($argument)) {
//                        array_push($classes, $this->getClasses($argument));
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
        }

        return join('  ', $classes);
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