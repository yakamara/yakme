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

class Arr
{

    /**
     * Insert a value or key/value pair after a specific key in an array.  If key doesn't exist, value is appended
     * to the end of the array.
     *
     * @param array $array
     * @param string $afterKey
     * @param array $insertArray
     *
     * @return array
     */
    public static function insertAfterKey(array $array, $afterKey, array $insertArray)
    {
        $keys = array_keys($array);
        $index = array_search($afterKey, $keys);
        $position = false === $index ? count($array) : $index + 1;
        return array_merge(array_slice($array, 0, $position), $insertArray, array_slice($array, $position));
    }

    /**
     * Move a value or key/value pair after a specific key in an array.
     *
     * @param array $array
     * @param string $afterKey
     * @param string $moveKey
     *
     * @return array
     */
    public static function moveAfterKey(array $array, $afterKey, $moveKey)
    {
        if (!isset($array[$moveKey])) {
            return $array;
        }

        $insertArray = [$moveKey => $array[$moveKey]];
        unset($array[$moveKey]);
        return self::insertAfterKey($array, $afterKey, $insertArray);
    }
}
