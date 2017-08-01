<?php

if (!function_exists('convertMarkup')) {
    function convertMarkup($markup, $parser = 'textile') {
        return \Yakme\Yakme::convertMarkup($markup, $parser);
    }
}

if (!function_exists('widont')) {
    function widont($string) {
        return \Yakme\Yakme::widont($string);
    }
}
