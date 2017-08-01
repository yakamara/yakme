<?php

/**
 * @var rex_addon $this
 */

class_alias('\Yakme\Download', 'Download');
class_alias('\Yakme\Media', 'Media');
class_alias('\Yakme\Sort', 'Sort');
class_alias('\Yakme\Yakme', 'Yakme');

require_once __DIR__ . '/lib/Yakme/helper.php';
require_once __DIR__ . '/functions/textile_helper.php';


if (!function_exists('trans')) {
    function trans($value) {
        return \Yakme::trans($value);
    }
}

rex_extension::register('MEDIA_URL_REWRITE', function (rex_extension_point $ep) {
    $object = $ep->getParam('media');
    if ($object->media->getExtension() == 'svg') {
        return \rex_url::media($object->media->getFileName());
    }
    return rex_url::frontend('images/' . $object->getMediaType() . '/' . $object->media->getFileName());
}, rex_extension::EARLY);


// htaccess
// RewriteRule ^download/([^/]*) %{ENV:BASE}/index.php?download_file=$1&%{QUERY_STRING} [B]
$downloadFile = \rex_request::request('download_file', 'string');
if ($downloadFile != '') {
    $download = new Download($downloadFile);
    $download->force();
}


if (\rex::isBackend()) {
    \rex_view::addCssFile($this->getAssetsUrl('modules/tabbed.css'));
    \rex_view::addJsFile($this->getAssetsUrl('modules/tabbed.js'));
}

// YDeploy
// - - - - - - - - - - - - - - - - - - - - - - - - - -
if (\rex::isBackend() && \rex_addon::get('ydeploy')->isAvailable()) {
    \rex_view::addCssFile($this->getAssetsUrl('css/ydeploy.css'));
    if (\rex_addon::get('project')->getConfig('env') == 'production') {
        \rex_view::addCssFile($this->getAssetsUrl('css/ydeploy-production.css'));
    } else {
        \rex_view::addCssFile($this->getAssetsUrl('css/ydeploy-development.css'));
    }
}
