<?php

namespace Yakme;

class Yakme
{

    public static function elixir($file, $media = 'all')
    {
        $path = \rex_path::frontend(\rex_path::absolute($file));

        if (!file_exists($path)) {
            return '';
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if (!in_array($extension, ['css', 'js', 'svg'])) {
            return '';
        }

        $file = \rex_url::frontend($file). '?v='. filemtime($path);
        switch ($extension) {
            case 'css':
                return '<link rel="stylesheet" type="text/css" media="' . $media . '" href="' . $file .'" />';
            case 'js':
                return '<script type="text/javascript" src="' . $file .'"></script>';
            case 'svg':
                return '<div id="svg-symbols" style="display: none;">' . \rex_file::get($path) . '</div>';
        }

        return '';
    }

    public static function trans($value)
    {
        return \rex_addon::get('project')->i18n($value);
    }



    /* Convert markup to html
     *
     * @param  string     $markup
     * @param  string     $parser
     *
     * @return string
     */
    public static function convertMarkup($markup, $parser = 'textile')
    {
        if ($markup == '') {
            return false;
        }

        $prefix = '';
        $suffix = '';
        if (is_array($markup)) {
            $prefix = (isset($markup['prefix'])) ? $markup['prefix'] : '';
            $suffix = (isset($markup['suffix'])) ? $markup['suffix'] : '';
            $subject = (isset($markup['subject'])) ? $markup['subject'] : '';
            $subject = (isset($markup['output']) && $markup['output'] == 'html') ? $subject : str_replace('<br />', '', htmlspecialchars_decode($subject));
            $markup = $subject;
        } else {
            $markup = htmlspecialchars_decode($markup);
            $markup = str_replace('<br />', '', $markup);
        }

        switch ($parser) {
            case 'textile':
                if (\rex_addon::get('markitup')->isAvailable()) {
                    return $prefix . \markitup::parseOutput('textile', $markup) . $suffix;
                }
                return $prefix . \rex_textile::parse($markup) . $suffix;
            case 'markdown':
                return $prefix . (new \ParsedownExtra)->text($markup) . $suffix;
        }

    }



    /**
     * Avoid widows in a string
     *
     * @param string $string
     * @return string
     */
    public static function widont($string)
    {
        // Sollte ein Wort allein auf einer Zeile vorkommen, wird dies unterbunden
        $string = rtrim($string);
        $space = strrpos($string, ' ');
        if ($space !== false) {
            $string = substr($string, 0, $space) . '&#160;' . substr($string, $space + 1);
        }
        return $string;
    }
}
