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

namespace Yakme\Extension;

class MediaManagerFilterset
{
    public static function handleResponsiveImages(\rex_extension_point $ep)
    {

        $params = $ep->getParams(); // let's get the parameters
        $subject = $ep->getSubject(); // and the effects array
        $mediaSourcePosition = strpos($params['rex_media_type'], '--');

        if($mediaSourcePosition > 0) {
            $type = substr($params['rex_media_type'], 0, $mediaSourcePosition);
            $size = (int)substr($params['rex_media_type'], $mediaSourcePosition + 2);

            $media = new \rex_managed_media('/');
            $mediaManager = new \rex_media_manager($media);
            $effects = $mediaManager->effectsFromType($type);

            if ($size > 0 && count($effects) > 0) {
                foreach ($effects as $index => $effect) {
                    if (isset($effect['params']['width'])) {

                        if (isset($effect['params']['height']) && (int)$effect['params']['height'] > 0) {
                            $effect['params']['height'] = (int)$effect['params']['height'] * $size / (int)$effect['params']['width'];
                        }

                        $effect['params']['width'] = $size;
                    }
                    $effects[$index] = $effect;
                }
            }

            $subject = $effects;
        }
        $ep->setSubject($subject);
    }
}
