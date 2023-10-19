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


namespace Yakme\Html;

class SectionContainer
{
    const HTML_PREPEND = 1;
    const HTML_INNER = 2;
    const HTML_APPEND = 3;

    protected $attributes;
    protected $name;
    protected $html = [];

    public static $isOpened = false;
    public static $appendHtml = '';

    public function __construct($name)
    {
        $this->name = \rex_string::normalize(strtolower($name), '-');
    }

    public function setAttributes($value)
    {
        return $this->attributes = $value;
    }

    public function setHTML($value, $position = self::HTML_INNER)
    {
        return $this->html[$position][] = $value;
    }

    public function build()
    {
        $html = '';
        if (self::$isOpened) {
            $html .= sprintf('</div>%s</section>', static::$appendHtml);
            static::$appendHtml = '';
        }

        $this->attributes = 'data-section="' . $this->name . '" ' . $this->attributes;

        if (null === $this->attributes || strpos($this->attributes, 'class') === false) {
            $this->attributes = 'class="section" ' . $this->attributes;
        }

        $htmlAppend = '';
        $htmlInner = '';
        $htmlPrepend = '';
        if (count($this->html)) {
            if (isset($this->html[self::HTML_PREPEND])) {
                $htmlPrepend = implode('', $this->html[self::HTML_PREPEND]);
            }
            if (isset($this->html[self::HTML_INNER])) {
                $htmlInner = implode('', $this->html[self::HTML_INNER]);
            }
            if (isset($this->html[self::HTML_APPEND])) {
                $htmlAppend = implode('', $this->html[self::HTML_APPEND]);
            }
        }

        $html .= sprintf('<section%s>%s<div class="section-container">%s', ' ' . $this->attributes, $htmlPrepend, $htmlInner);

        static::$appendHtml = $htmlAppend;
        static::$isOpened = true;

        return $html;
    }
}
