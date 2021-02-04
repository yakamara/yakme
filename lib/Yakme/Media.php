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

namespace Yakme;

class Media
{
    /* @var \rex_media */
    public $media;
    private $mediaType = 'max';
    private $pictureAttributes = [];
    private $pictureSources = [];
    private $srcset = [
        '200' => '200w',
        '400' => '400w',
        '800' => '800w',
        '1200' => '1200w',
        '1600' => '1600w',
        '2000' => '2000w',
        '2400' => '2400w',
    ];
    private $usePicture = false;
    private $imageLink = [];
    private $imageSizes = [];
    private $caption = [];
    private $copyright = [];
    private $audioTypes = [
        'mp3' => 'audio/mp3',
        'ogg' => 'audio/ogg',
        'wav' => 'audio/wav',
    ];
    private $videoTypes = [
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
        'ogg' => 'video/ogg',
    ];

    public function __construct($name)
    {
        $this->media = \rex_media::get($name);
    }

    /**
     * @param string $name
     *
     * @return null|static
     */
    public static function get($name)
    {
        $instance = new self($name);
        if ($instance->media) {
            return $instance;
        }

        return null;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = \rex_extension::registerPoint(new \rex_extension_point('MEDIA_URL_REWRITE', '', ['media' => $this]));
        return $url ?: \rex_url::media($this->media->getFileName());
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if (\rex_clang::getCurrentId() >= 2) {
            $value = $this->media->getValue('med_title_'.\rex_clang::getCurrentId());
            $value = $value ?: '';
            return $value;
        }

        $value = $this->media->getTitle();
        $value = $value ?: '';
        return $value;
    }

    /**
     * @return string
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * @param string $type
     *
     * @return $this|\Yakme\Media
     */
    public function setMediaType($type)
    {
        if ($type !== '') {
            $this->mediaType = $type;
        }
        return $this;
    }

    /**
     * @param array $srcSet
     *
     * @return $this|\Yakme\Media
     */
    public function setSrcSet(array $srcSet)
    {
        $this->srcset = $srcSet;
        return $this;
    }

    /**
     * @return $this|\Yakme\Media
     */
    public function usePicture()
    {
        $this->usePicture = true;
        return $this;
    }

    /**
     * @param string $media
     * @param string $sizes
     * @param string $mediaType
     *
     * @return $this|\Yakme\Media
     */
    public function addPictureSource($media, $sizes = '', $mediaType = '')
    {
        $this->usePicture();

        if ($mediaType == '') {
            $mediaType = $this->mediaType;
        }
        $this->pictureSources[] = ['media' => $media, 'sizes' => $sizes, 'mediaType' => $mediaType];
        return $this;
    }

    public function setPictureAttributes($attributes)
    {
        $this->pictureAttributes = array_merge($this->pictureAttributes, $attributes);
    }

    /**
     * @return string
     */
    public function getImageSrcSet()
    {
        $filename = $this->getUrl();
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        $extension = $this->media->getExtension();

        if (count($this->srcset) > 0 && $this->isRasterFile()) {
            $srcSet = [];
            foreach ($this->srcset as $index => $size) {
                $srcSet[$size] = '/'.$index.'/'.$basename.'.'.$extension;
            }

            $tmpPath = pathinfo($filename, PATHINFO_DIRNAME);
            $sources = [];
            foreach ($srcSet as $size => $file) {
                $sources[] = $tmpPath.$file.' '.$size;
            }

            return implode(','."\n", $sources);
        }

        return false;
    }

    /**
     * @param string $url
     * @param array  $attributes
     *
     * @return $this|\Yakme\Media
     */
    public function addImageLink($url, array $attributes = [])
    {
        if ($url !== '') {
            $this->imageLink['url'] = $url;
            $this->imageLink['attributes'] = $attributes;
        }
        return $this;
    }

    /**
     * @param string $sizes
     *
     * @return $this|\Yakme\Media
     */
    public function addImageSizes($sizes)
    {
        $this->imageSizes = $sizes;
        return $this;
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getCaption($field = 'med_caption')
    {
        $value = $this->media->getValue($field);
        if (is_null($value)) {
            $value = '';
        }
        if ($value != '') {
            return trim($value);
        }

        $value = isset($this->caption['value']) ? $this->caption['value'] : '';
        if ($value != '') {
            return $value;
        }

        return '';
    }

    /**
     * @param string $value
     * @param array  $attributes
     *
     * @return $this|\Yakme\Media
     */
    public function setCaption($value, array $attributes = [])
    {
        if ($value != '') {
            $this->caption['attributes'] = $attributes;
            $this->caption['value'] = $value;
        }

        return $this;
    }

    /**
     * @param array  $attributes
     * @param string $field
     *
     * @return $this|\Yakme\Media
     */
    public function withCaption(array $attributes = [], $field = 'med_caption')
    {
        $value = $this->getCaption($field);
        if ($value != '') {
            $this->caption['attributes'] = $attributes;
            $this->caption['value'] = $value;
        }

        return $this;
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getCopyright($field = 'med_copyright')
    {
        $value = $this->media->getValue($field);
        if (is_null($value)) {
            $value = '';
        }
        $value = trim($value);
        if ($value != '') {
            return $value;
        }

        return '';
    }

    /**
     * @param string $value
     * @param array  $attributes
     *
     * @return $this|\Yakme\Media
     */
    public function setCopyright($value, array $attributes = [])
    {
        if ($value != '') {
            $this->copyright['attributes'] = $attributes;
            $this->copyright['value'] = $value;
        }

        return $this;
    }

    /**
     * @param array  $attributes
     * @param string $field
     *
     * @return $this|\Yakme\Media
     */
    public function withCopyright(array $attributes = [], $field = 'med_copyright')
    {
        $value = $this->getCopyright($field);
        if ($value != '') {
            $this->copyright['attributes'] = $attributes;
            $this->copyright['value'] = $value;
        }

        return $this;
    }

    /**
     * @param array $attributes
     * @param array $attributesParams
     *
     * @return string
     */
    public function toFigure(array $attributes = [], array $attributesParams = [])
    {
        $image = '';
        if ($this->usePicture === true) {
            $image = $this->getPictureTag([], $attributesParams);
        } else {
            $image = $this->getImageTag($attributesParams);
        }

        if ($image == '') {
            return '';
        }

        $caption = '';
        if ($this->caption) {
            $caption = sprintf('<figcaption%s>%s</figcaption>', \rex_string::buildAttributes($this->caption['attributes']), $this->caption['value']);
        }
        $copyright = '';
        if ($this->copyright) {
            $copyright = sprintf('<footer%s><small>&copy; %s</small></footer>', \rex_string::buildAttributes($this->copyright['attributes']), $this->copyright['value']);
        }

        return sprintf('<figure%s>%s%s%s</figure>', \rex_string::buildAttributes($attributes), $image, $copyright, $caption);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function toImage(array $params = [])
    {
        $image = $this->getImageTag($params);

        if ($image == '') {
            return '';
        }

        $caption = '';
        if ($this->caption) {
            $caption = sprintf('<span%s>%s</span>', \rex_string::buildAttributes($this->caption['attributes']), $this->caption['value']);
        }
        $copyright = '';
        if ($this->copyright) {
            $copyright = sprintf('<footer%s><small>&copy; %s</small></footer>', \rex_string::buildAttributes($this->copyright['attributes']), $this->copyright['value']);
        }

        return $image.$copyright.$caption;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function toPicture(array $attributes = [], array $attributesParams = [])
    {
        $picture = $this->getPictureTag($attributes, $attributesParams);

        if ($picture == '') {
            return '';
        }

        $caption = '';
        if ($this->caption) {
            $caption = sprintf('<span%s>%s</span>', \rex_string::buildAttributes($this->caption['attributes']), $this->caption['value']);
        }
        $copyright = '';
        if ($this->copyright) {
            $copyright = sprintf('<footer%s><small>&copy; %s</small></footer>', \rex_string::buildAttributes($this->copyright['attributes']), $this->copyright['value']);
        }

        return $picture.$copyright.$caption;
    }

    public function getIcon($useDefaultIcon = true)
    {
        $ext = $this->media->getExtension();
        $folder = \rex_url::addonAssets('project', 'mimeicons');
        $icon = '/mime-'.$ext.'.gif';

        // Dateityp für den kein Icon vorhanden ist
        if (!file_exists(\rex_path::addonAssets('project', 'mimeicons'.$icon))) {
            if ($useDefaultIcon) {
                $icon = '/mime-default.gif';
            } else {
                $icon = '/mime-error.gif';
            }
        }
        return $folder.$icon;
    }

    public function toIcon($iconAttributes = [])
    {
        $ext = $this->media->getExtension();
        $icon = $this->getIcon();

        if (!isset($iconAttributes['alt'])) {
            $iconAttributes['alt'] = '&quot;'.$ext.'&quot;-Symbol';
        }

        if (!isset($iconAttributes['title'])) {
            $iconAttributes['title'] = $iconAttributes['alt'];
        }

        if (!isset($iconAttributes['style'])) {
            $iconAttributes['style'] = 'width: 44px; height: 38px';
        }

        return '<img src="'.$icon.'"'.\rex_string::buildAttributes($iconAttributes).' />';
    }

    /**
     * @param string $label
     * @param array  $attributes
     *
     * @return string
     */
    public function toDownloadLink($label = '', array $attributes = [])
    {
        if ($label == '') {
            $label = $this->getTitle();
        }

        if ($label == '') {
            $label = $this->media->getFileName();
        }

        //return '<a href="/download/' . $this->media->getFileName() . '">' . $label . '</a>';
        return sprintf('<a%s href="%s">%s</a>', \rex_string::buildAttributes($attributes), $this->getDownloadUrl(), $label);
    }

    public function getDownloadUrl()
    {
        return '/download/'.$this->media->getFileName();
    }

    public function getMimeExtension()
    {
        $extension = $this->media->getExtension();
        switch ($extension) {
            case 'docm':
            case 'docx':
            case 'xlsm':
            case 'xlsx':
            case 'pptm':
            case 'pptx':
                $extension = substr($extension, 0, -1);
                break;
        }
        return $extension;
    }

    public function isVideo()
    {
        return isset($this->videoTypes[$this->media->getExtension()]);
    }

    public function toVideo(array $attributes = [])
    {
        $filename = substr($this->media->getFilename(), 0, strrpos($this->media->getFilename(), '.'));

        $videoAttributes = [
            'width' => '100%',
            'height' => '100%',
            'controls' => 'controls',
            'preload' => 'none',
        ];

        $videoFlash = '';
        $videoSource = '';
        $videoImgSrc = '';
        if ($this->media->getValue('med_poster') != '') {
            $posterObject = \rex_media::get($this->media->getValue('med_poster'));

            $videoAttributes['poster'] = \rex_url::media($posterObject->getFileName());
            $videoImgSrc = \rex_url::media($posterObject->getFileName());
        }

        foreach ($this->videoTypes as $extension => $type) {
            $videoObject = \rex_media::get($filename.'.'.$extension);
            if ($videoObject) {
                $videoSource .= '<source type="'.$type.'" src="'.\rex_url::media($videoObject->getFileName()).'" />';

                if ($type == 'mp4') {
                    $videoFlash = '
                    <object width="100%" height="100%" type="application/x-shockwave-flash" data="'.\rex_url::assets('vendor/mediaelement/build/mediaelement-flash-video.swf').'">
                        <param name="movie" value="'.\rex_url::assets('vendor/mediaelement-flash-video.swf').'" />
                        <param name="flashvars" value="controls=true&amp;poster='.$videoImgSrc.'&amp;file='.\rex_url::media($videoObject->getFileName()).'" />
                        <img src="'.$videoImgSrc.'" title="No video playback capabilities" />
                    </object>';
                }
            }
        }

        $attributes = array_filter(array_merge($videoAttributes, $attributes), function($item) { return !is_null($item); });

        return '
            <video'.\rex_string::buildAttributes($attributes).'>
                '.$videoSource.'
                '.$videoFlash.'
            </video>';
    }

    public function isAudio()
    {
        return isset($this->audioTypes[$this->media->getExtension()]);
    }

    public function toAudio(array $attributes = [])
    {
        $filename = substr($this->media->getFilename(), 0, strrpos($this->media->getFilename(), '.'));
        $audioAttributes = [
            'controls' => 'controls',
        ];

        $attributes = array_merge($audioAttributes, $attributes);

        $sources = [];
        foreach ($this->audioTypes as $extension => $type) {
            $audioObject = \rex_media::get($filename.'.'.$extension);
            if ($audioObject) {
                $sources[] = sprintf('<source src="%s" type="%s" />', \rex_url::media($audioObject->getFileName()), $type);
            }
        }

        return '
            <audio'.\rex_string::buildAttributes($attributes).'>
                '.implode('', $sources).'
            </audio>';
    }

    /**
     * @return bool
     */
    public function isRasterFile()
    {
        return self::isRasterType($this->media->getExtension());
    }

    public static function getRasterTypes()
    {
        return array_diff(\rex_addon::get('mediapool')->getProperty('image_extensions'), ['svg']);
    }

    public static function isRasterType($extension)
    {
        return in_array($extension, self::getRasterTypes());
    }

    /**
     * @param string $image
     *
     * @return string
     */
    protected function getImageLink($image)
    {
        if (isset($this->imageLink['url'])) {
            $image = '<a href="'.$this->imageLink['url'].'"'.\rex_string::buildAttributes($this->imageLink['attributes']).'>'.$image.'</a>';
        }
        return $image;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    private function getImageTag(array $params = [])
    {
        if (!$this->media->isImage()) {
            return '';
        }

        $filename = \rex_url::media($this->media->getFileName());
        if ($this->isRasterFile()) {
            $filename = $this->getUrl();
        }

        $title = $this->getTitle();

        if (!isset($params['alt'])) {
            $params['alt'] = htmlspecialchars($title);
        }

        if (!isset($params['title'])) {
            if ($title != '') {
                $params['title'] = htmlspecialchars($title);
            }
        }

        \rex_extension::registerPoint(new \rex_extension_point('MEDIA_TOIMAGE', '', ['filename' => &$filename, 'params' => &$params]));
        $image = sprintf('<img src="%s"%s />', $filename, \rex_string::buildAttributes($params));
        $image = $this->getImageLink($image);
        return $image;
    }

    /**
     * @param array $attributesPicture
     * @param array $attributesImage
     *
     * @return string
     */
    private function getPictureTag(array $attributesPicture = [], array $attributesImage = [])
    {
        if (!$this->media->isImage()) {
            return '';
        }

        $attributesPicture = array_merge($this->pictureAttributes, $attributesPicture);

        $filename = $this->getUrl();
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        $extension = $this->media->getExtension();

        if (!$this->isRasterFile()) {
            $filename = \rex_url::media($this->media->getFileName());
        }

        $title = $this->getTitle();

        if (!isset($attributesImage['alt'])) {
            $attributesImage['alt'] = htmlspecialchars($title);
        }

        if (!isset($attributesImage['title'])) {
            if ($title != '') {
                $attributesImage['title'] = htmlspecialchars($title);
            }
        }

        if (!\rex::isBackend() && !isset($attributesImage['width'])) {
            $attributesImage['width'] = '100%';
        }

        $srcSet = [];
        if (count($this->srcset) > 0 && $this->isRasterFile()) {
            foreach ($this->srcset as $index => $size) {
                $srcSet[$size] = '/'.$index.'/'.$basename.'.'.$extension;
            }
        }

        $sourceTags = [];
        if (count($this->pictureSources) > 0) {
            foreach ($this->pictureSources as $index => $pictureSource) {
                $attributes = [];
                if ($pictureSource['media'] != '') {
                    $attributes['media'] = $pictureSource['media'];
                }
                if ($pictureSource['sizes'] != '') {
                    $attributes['sizes'] = $pictureSource['sizes'];
                }
                if ($pictureSource['mediaType'] != '') {
                    $tmpMedia = clone $this;
                    $tmpPath = pathinfo($tmpMedia->setMediaType($pictureSource['mediaType'])->getUrl(), PATHINFO_DIRNAME);
                    $sources = [];
                    foreach ($srcSet as $size => $file) {
                        $sources[] = $tmpPath.$file.' '.$size;
                    }
                    $attributes['srcset'] = implode(','."\n", $sources);
                }

                $sourceTags[] = sprintf('<source%s />', \rex_string::buildAttributes($attributes));
            }
        }

        $tmpPath = pathinfo($filename, PATHINFO_DIRNAME);
        $filename = str_replace($this->getMediaType().'/', $this->getMediaType().'/400/', $filename);

        $sources = [];
        foreach ($srcSet as $size => $file) {
            $sources[] = $tmpPath.$file.' '.$size;
        }
        if (count($sources)) {
            $attributesImage['srcset'] = implode(','."\n", $sources);
        }
        if ($this->imageSizes != '') {
            $attributesImage['sizes'] = $this->imageSizes;
        }

        $image = sprintf('<picture%s>%s<img src="%s"%s /></picture>', \rex_string::buildAttributes($attributesPicture), implode("\n", $sourceTags), $filename, \rex_string::buildAttributes($attributesImage));
        $image = $this->getImageLink($image);
        return $image;
    }
}
