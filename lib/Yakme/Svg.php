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

class Svg
{
    public static function get($id, $title = null, $class = null)
    {
        return '<svg'.($class ? ' class="'.$class.'"' : '').' aria-hidden="true">'.($title ? '<title>'.$title.'</title>' : '').'<use xlink:href="#'.$id.'"></use></svg>';
    }

    public static function file($file)
    {
        $path = \rex_path::frontend(\rex_path::absolute($file));
        return \rex_file::get($path);
    }

    public static function image($file, $title = null, $class = null)
    {
        $path = \rex_path::frontend(\rex_path::absolute($file));
        if (file_exists($path)) {
            return '<img'.($class ? ' class="'.$class.'"' : '').' src="'.$file.'"'.($title ? ' alt="'.rex_escape($title).'"' : '').' />';
        }
        return null;
    }
}
