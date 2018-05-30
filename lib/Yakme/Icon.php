<?php declare(strict_types=1);

/**
 * This file is part of the Yakme package.
 *
 * @author (c) Yakamara Media GmbH & Co. KG
 * @author     Thomas Blum <thomas.blum@yakamara.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakme;

class Icon
{
    public static function get($id, $classes = '', $prefix = 'icon')
    {
        if ($classes == '') {
            $classes = [$id];
        }
        if (is_string($classes)) {
            $classes = explode(' ', $classes);
        }

        $class = $prefix;

        if ($prefix != '') {
            $prefix .= '-';
        }

        if (count($classes) >= 1 && $prefix != '') {
            $class .= ' '.trim($prefix.implode(' '.$prefix, $classes));
        } elseif (count($classes) >= 1) {
            $class .= ' '.trim(implode(' ', $classes));
        }

        $id = $prefix.$id;
        // $class = trim('icon icon-' . $class);
        return Svg::get($id, null, $class);
    }

    public static function circle($id, $classes = '', $prefix = 'icon')
    {
        return '<span class="icon-circle-box">'.self::get($id, $classes, $prefix).'</span>';
    }
}
