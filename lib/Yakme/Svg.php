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
        return '<svg' . ($class ? ' class="' . $class . '"' : '') .' aria-hidden="true">' . ($title ? '<title>' . $title . '</title>' : '') . '<use xlink:href="#' . $id . '"></use></svg>';
    }
}
