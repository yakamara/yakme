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

class rex_effect_responsive extends rex_effect_abstract
{
    const SEPARATOR = '-yakme-';
    protected static $sizes = [200, 400, 800, 1200, 1600, 2000, 2400];

    public function execute()
    {
        $file = rex_media_manager::getMediaFile();
        $data = self::split($file);
        $this->media->setMediaPath(rex_path::media($data['file']));
        $this->media->setFormat($data['format']);
    }


    public function getName()
    {
        return rex_i18n::msg('yakme_media_manager_effect_responsive');
    }


    public static function handle(\rex_extension_point $ep)
    {
        $effects = $ep->getSubject(); // and the effects array
        $filename = rex_media_manager::getMediaFile();
        $data = self::split($filename);

        if (!$data['size'] || count($effects) < 1) {
            return;
        }

        foreach ($effects as $index => $effect) {
            if (isset($effect['params']['width'])) {
                if (isset($effect['params']['height']) && (int)$effect['params']['height'] > 0) {
                    $effect['params']['height'] = ceil((int)$effect['params']['height'] * $data['size'] / (int)$effect['params']['width']);
                }
                $effect['params']['width'] = $data['size'];
            }
            $effects[$index] = $effect;
        }
        $ep->setSubject($effects);
    }

    /**
     * @param $sizes array
     */
    public static function setSizes($sizes)
    {
        self::$sizes = $sizes;
    }


    public static function split($filename)
    {
        $file = $filename;
        $format = null;
        $size = null;

        $position = strpos($filename, self::SEPARATOR);
        if(false !== $position) {
            $file = substr($filename, 0, $position);
            $size = (int)substr($filename, $position + strlen(self::SEPARATOR));
            $format = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($size, self::$sizes)) {
                $size = null;
            }
        }
        return compact('file', 'format', 'size');
    }
}
