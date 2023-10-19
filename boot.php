<?php

/**
 * @var rex_addon $this
 */

use Yakme\Download;
use Yakme\Html\Section;
use Yakme\Html\SectionOption;
use Yakme\Yakme;

//class_alias('\Yakme\Download', 'Download');
//class_alias('\Yakme\Media', 'Media');
//class_alias('\Yakme\MediaQuery', 'Mq');
//class_alias('\Yakme\Sort', 'Sort');
//class_alias('\Yakme\Yakme', 'Yakme');

require_once __DIR__ . '/lib/Yakme/helper.php';
require_once __DIR__ . '/functions/textile_helper.php';


if (!function_exists('trans')) {
    function trans($value) {
        return Yakme::trans($value);
    }
}


if (rex_addon::get('media_manager')->isAvailable()) {
    rex_media_manager::addEffect(\rex_effect_responsive::class);
}

// rex_media_manager::deleteCache();
rex_extension::register('MEDIA_MANAGER_FILTERSET', '\rex_effect_responsive::handle', rex_extension::EARLY);


rex_extension::register('MEDIA_URL_REWRITE', function (rex_extension_point $ep) {
    $object = $ep->getParam('media');
    if ($object->media->getExtension() == 'svg') {
        return \rex_url::media($object->media->getFileName());
    }
    // $type = (\rex::isBackend()) ? 'rex_mediapool_maximized' : $object->getMediaType();
    $type = $object->getMediaType();
    if (\rex::isBackend()) {
        $type = 'rex_mediabutton_preview';
        $object->setSrcSet([]);
    }

    $path = 'images/' . $type . '/';
    if ($ep->getParam('size')) {
        $path .= $ep->getParam('size') . '/';
    }

    return rex_url::frontend($path . $object->media->getFileName());
}, rex_extension::EARLY);


\rex_extension::register('MEDIA_IS_IN_USE', '\Yakme\Extension\YForm::isMediaInUse');
\rex_extension::register('PACKAGES_INCLUDED', '\Yakme\Extension\YForm::isArticleInUse');

// Html Section
// - - - - - - - - - - - - - - - - - - - - - - - - - -
$options = $this->getProperty('html_section_options');
$options = \rex_extension::registerPoint(new \rex_extension_point('YAKME_HTML_SECTION_OPTION', $options));

$registeredOptions = [];
if (count($options) > 0) {
    foreach ($options as $option) {
        /* @var $instance SectionOption */
        $instance = new $option();
        $registeredOptions[$instance->name()] = $instance;
    }
    Section::setAvailableOptions($registeredOptions);
}
if (!rex::isBackend()) {
    \rex_extension::register('YAKME_OUTPUT_ARTICLE_CONTENT', '\Yakme\Extension\HtmlSection::parse');
}

// Download
// - - - - - - - - - - - - - - - - - - - - - - - - - -
// htaccess
// RewriteRule ^download/([^/]*) %{ENV:BASE}/index.php?download_file=$1&%{QUERY_STRING} [B]
$downloadFile = \rex_request::request('download_file', 'string');
if ($downloadFile != '') {
    $download = new Download($downloadFile);
    $download->force();
}


if (\rex::isBackend()) {
    \rex_view::addCssFile($this->getAssetsUrl('css/quick-navigation.css'));
    \rex_view::addCssFile($this->getAssetsUrl('modules/tabbed.css'));
    \rex_view::addJsFile($this->getAssetsUrl('modules/tabbed.js'));

    if (\rex_be_controller::getCurrentPagePart(1) == 'content') {
        \rex_view::addCssFile($this->getAssetsUrl('css/meta-info.css'));
        \rex_view::addCssFile($this->getAssetsUrl('css/slice.css'));

        \rex_extension::register('OUTPUT_FILTER', function(\rex_extension_point $ep) {
            $article = \rex_article::get(rex_request('article_id', 'int'));
            if ($article) {
                $level = count($article->getPathAsArray());
                $ep->setSubject(
                    str_replace(
                        'class="rex-main-frame"',
                        'class="rex-main-frame" data-structure-level="' . $level . '"',
                        $ep->getSubject()
                    )
                );
            }
        });
    }
}

// YDeploy
// - - - - - - - - - - - - - - - - - - - - - - - - - -
if (\rex::isBackend() && \rex_addon::get('ydeploy')->isAvailable()) {
    rex_extension::register('YDEPLOY_BADGE', function(rex_extension_point $ep) {
        $project = \rex_addon::get('project');
        $version = isset($project->getProperty('app')['version']) ? ' - <small>Version ' . $project->getProperty('app')['version'] . '</small>' : '';
        $ep->setSubject($ep->getSubject() . $version);
    });
}



if (\rex::isBackend() && \rex::getUser() && \rex::getUser()->isAdmin() && \rex_plugin::get('yform', 'manager') && count(\rex_yform_manager_table::getAll()) > 0) {
    \rex_extension::register('PAGES_PREPARED', function (\rex_extension_point $ep) {
        $pages = $ep->getSubject();

        $highAddOns = [
            'media_manager',
            'metainfo',
            'sprog',
            'yform',
        ];

        $highAddOnPages = [];
        $lowAddOnPages = [];
        /* @var $page \rex_be_page_main */
        foreach ($pages as $index => $page) {
            if ($page instanceof \rex_be_page_main && $page->getBlock() == 'addons') {
                if (in_array($page->getKey(), $highAddOns)) {
                    $page->setBlock('high_addons');
                    $highAddOnPages[$page->getKey()] = $page;
                } else {
                    $page->setBlock('low_addons');
                    $lowAddOnPages[$page->getKey()] = $page;
                }
                unset($pages[$index]);
            }
        }
        $pages = array_merge($pages, $highAddOnPages, $lowAddOnPages);
        $ep->setSubject($pages);
    }, \rex_extension::LATE);

    \rex_extension::register('OUTPUT_FILTER', function(\rex_extension_point $ep) {
        $ep->setSubject(
            str_replace(
                [
                    '[translate:navigation_high_addons]',
                    '[translate:navigation_low_addons]'
                ], [
                    \rex_i18n::msg('navigation_addons'),
                    \rex_i18n::msg('navigation_addons') . ' (Andere)'
                ]
                ,
                $ep->getSubject()
            )
        );
    });
}
