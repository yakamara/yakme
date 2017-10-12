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
    $type = (\rex::isBackend()) ? 'rex_mediapool_maximized' : $object->getMediaType();
    return rex_url::frontend('images/' . $type . '/' . $object->media->getFileName());
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

    rex_extension::register('OUTPUT_FILTER', function(rex_extension_point $ep) {
        $project = \rex_addon::get('project');
        $env = $project->getConfig('env') == 'production' ? 'Production' : 'Development';
        $version = isset($project->getProperty('app')['version']) ? ' - <small>Version ' . $project->getProperty('app')['version'] : '';
        $ep->setSubject(
            str_replace(
                '</body>',
                '<div class="ydeploy-badge">' . $env . $version . '</small></div></body>',
                $ep->getSubject()
            )
        );
    });
}

// rex_media_manager::deleteCache();
rex_extension::register('MEDIA_MANAGER_FILTERSET', '\Yakme\Extension\MediaManagerFilterset::handleResponsiveImages', rex_extension::EARLY);
