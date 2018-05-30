<?php declare(strict_types=1);

/**
 * This file is part of the Cheatsheet package.
 *
 * @author (c) Thomas Blum <thomas@addoff.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakme;

class Sort
{
    /**
     * @param string $field   Object property, Array key, Callable method
     * @param array  $array   Array to sort
     * @param string $order   The order of the sort ASC | DESC
     * @param string $compare Function to compare
     *
     * @return array
     */
    public static function by($field, $array, $order = 'ASC', $compare = null)
    {
        $method = false;
        if (substr($field, -2) == '()') {
            $method = true;
            $field = substr($field, 0, (strlen($field) - 2));
        }
        $function = function ($a, $b) use ($field, $compare, $method) {
            $a = (is_object($a)) ? ((!$method && $a instanceof \rex_structure_element) ? $a->getValue($field) : ($method ? $a->{$field}() : $a->$field)) : $a[$field];
            $b = (is_object($b)) ? ((!$method && $b instanceof \rex_structure_element) ? $b->getValue($field) : ($method ? $b->{$field}() : $b->$field)) : $b[$field];

            if ($compare !== null) {
                return $compare($a, $b);
            }
            return $a <=> $b;
        };

        return self::execute($array, $function, $order);
    }

    public static function byName($array, $order = 'ASC')
    {
        return self::by('name', $array, $order, 'strcmp');
    }

    public static function byCreateDate($array, $order = 'ASC')
    {
        return self::by('createdate', $array, $order);
    }

    public static function byPriority($array, $order = 'ASC')
    {
        return self::by('priority', $array, $order);
    }

    public static function byUpdateDate($array, $order = 'ASC')
    {
        return self::by('updatedate', $array, $order);
    }

    protected static function execute($array, $function, $order = 'ASC')
    {
        usort($array, $function);
        if (strtoupper($order) == 'DESC') {
            $array = array_reverse($array);
        }
        return $array;
    }
}
