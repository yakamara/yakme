<?php

/**
 * This file is part of the Yakme package.
 *
 * @author (c) Yakamara Media GmbH & Co. KG
 * @author Thomas Blum <thomas.blum@yakamara.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakme;

class Html
{
    public static function getDownloadButton($url, $label = '{{ download }}')
    {
        return '<a class="button-download" href="' . $url . '"><i class="icon-download"></i>' . $label . '</a>';
    }

    public static function getDownloadLink($url, $label = '{{ download }}')
    {
        return '<a href="' . $url . '"><i class="icon-download"></i>' . $label . '</a>';
    }

    public static function getEmailLink($email, $label = null, array $attributes = [])
    {
        if (!$label) {
            $label = $email;
        }
        return sprintf('<a%s href="mailto:%s">%s</a>', \rex_string::buildAttributes($attributes), $email, $label);
    }

    public static function getLink($url, $label = null)
    {
        if (!$label) {
            $label = $url;
        }
        return '<a href="' . $url . '">' . $label . '</a>';
    }

    public static function getPhoneLink($number, $label = null, array $attributes = [])
    {
        if (!$label) {
            $label = $number;
        }
        return sprintf('<a%s href="tel:%s">%s</a>', \rex_string::buildAttributes($attributes), preg_replace('/[^0-9]+/', '', $number), $label);
    }

    public static function getUrlLink($url)
    {
        if (trim($url) == '') {
            return '';
        }

        $scheme = 'http://';
        $cleanUrl = $url;
        if (substr($url, 0, 7) == 'http://') {
            $cleanUrl = substr($url, 7);
        } elseif (substr($url, 0, 8) == 'https://') {
            $scheme = 'https://';
            $cleanUrl = substr($url, 8);
        }
        return self::getLink($scheme . $cleanUrl, $cleanUrl);
    }
}
