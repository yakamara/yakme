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
    /**
     * @param        $url
     * @param string $label
     *
     * @return string
     */
    public static function getDownloadButton($url, $label = '{{ download }}')
    {
        return '<a class="button-download" href="'.$url.'"><i class="icon-download"></i>'.$label.'</a>';
    }

    /**
     * @param        $url
     * @param string $label
     *
     * @return string
     */
    public static function getDownloadLink($url, $label = '{{ download }}')
    {
        return '<a href="'.$url.'"><i class="icon-download"></i>'.$label.'</a>';
    }

    /**
     * @param       $email
     * @param null  $label
     * @param array $attributes
     *
     * @return string
     */
    public static function getEmailLink($email, $label = null, array $attributes = [])
    {
        if (!$label) {
            $label = $email;
        }
        $attributes = self::prepareAttributes($attributes, ['class' => 'email-link']);
        return self::getLink('mailto:'.$email, $label, $attributes);
    }

    public static function getLink($url, $label = null, array $attributes = [])
    {
        if (!$label) {
            $label = $url;
        }
        $attributes = self::prepareAttributes($attributes, ['href' => $url]);
        return sprintf('<a%s>%s</a>', \rex_string::buildAttributes($attributes), $label);
    }

    /**
     * @param       $number
     * @param null  $label
     * @param array $attributes
     *
     * @return string
     */
    public static function getPhoneLink($number, $label = null, array $attributes = [])
    {
        if (!$label) {
            $label = $number;
        }
        $attributes = self::prepareAttributes($attributes, ['class' => 'phone-link']);
        return self::getLink('tel:'.preg_replace('/[^0-9]+/', '', $number), $label, $attributes);
    }

    /**
     * @param \DateTime $datetime
     * @param string    $format
     *
     * @return string
     */
    public static function getTimeTag(\DateTime $datetime, $format = '%d.%m.%Y')
    {
        return '<time datetime="'.$datetime->format('W3C').'">'.strftime($format, $datetime->getTimestamp()).'</time>';
    }

    /**
     * @param string $url
     * @param array  $attributes
     *
     * @return string
     */
    public static function getUrlLink($url, array $attributes = [])
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
        $attributes = self::prepareAttributes($attributes, ['class' => 'external-link']);
        return self::getLink($scheme.$cleanUrl, $cleanUrl, $attributes);
    }

    protected static function prepareAttributes($arrayA, $arrayB)
    {
        return array_merge_recursive($arrayA, $arrayB);
    }
}
