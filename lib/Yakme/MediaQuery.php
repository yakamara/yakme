<?php

namespace Yakme;

class MediaQuery
{
    public static function from($from)
    {
        return self::get($from);
    }

    public static function until($until)
    {
        return self::get(false, $until);
    }

    public static function get($from = false, $until = false, $and = false, $mediaType = 'all')
    {
        $minWidth = 0;
        $maxWidth = 0;
        $mediaQuery = '';

        // From: this breakpoint (inclusive)
        if ($from) {
            if (is_numeric($from)) {
                $minWidth = self::px2em($from);
                $minWidth = $from;
            } else {
                $minWidth = self::px2em(self::getBreakpointWidth($from));
                $minWidth = self::getBreakpointWidth($from);
            }
        }

        // Until: that breakpoint (exclusive)
        if ($until) {
            if (is_numeric($until)) {
                $maxWidth = self::px2em($until);
            } else {
                $maxWidth = (int)(substr(self::px2em(self::getBreakpointWidth($until)), 0, -2)) - 0.01 . 'em';
            }
        }

        if ($minWidth != 0) {
            $mediaQuery = $mediaQuery . ' and (min-width: ' . $minWidth . ')';
        }
        if ($maxWidth != 0) {
            $mediaQuery = $mediaQuery . ' and (max-width: ' . $maxWidth . ')';
        }
        if ($and) {
            $mediaQuery = $mediaQuery . ' and ' . $and;
        }


        // Remove unnecessary media query prefix 'all and '
        if ($mediaType == 'all' and $mediaQuery != '') {
            $mediaType = '';
            $mediaQuery = substr($mediaQuery, 5);
        }

        return trim($mediaType . ' ' . $mediaQuery);
    }

    protected static function px2em($px, $baseFontSize = '16px')
    {
        $unit = substr($px, -2);
        switch ($unit) {
            case 'em':
                return $px;
                break;
            case 'px':
                return (int)substr($px, 0, -2) / (int)substr($baseFontSize, 0, -2) . 'em';
                break;
        }

        return self::px2em((int)$px . 'px');
    }

    protected static function getBreakpointWidth($name)
    {
        $breakpoints = \rex_addon::get('project')->getProperty('breakpoints');
        if (!isset($breakpoints[$name])) {
            throw new \rex_exception(sprintf('Breakpoint "%s" was not found in $breakpoints.', $name));
        }
        return $breakpoints[$name];
    }
}
