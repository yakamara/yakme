Yakme AddOn
================================================================================

## Responsive images

### Beispiele

**Voraussetzungen für das Beispiel**

`.htaccess` öffnen und ergännzen

```
# -yakme- = Separator for responsive images
RewriteRule ^images/([^/]*)/([^/]*)/([^/]*) %{ENV:BASE}/index.php?rex_media_type=$1&rex_media_file=$3-yakme-$2&%{QUERY_STRING} [B]
```



Folgende Mediatypen anlegen

| Mediatyp | Effekte | Hinweise |
| ------------- | ------------- | ------------- |
| **header-16by9** _(16:9 Format)_ | :responsive&nbsp;images |  |
|  | :focuspoint fit | Breite: 2400px; Höhe: 1350px;<br /> Zoom: Ausschnitt größtmöglich wählen (100%); Ausrichten an: Fokuspunkt des Bildes |
| **header-4by3** _(4:3 Format)_ | :responsive&nbsp;images |  |
| |  :focuspoint fit | Breite: 2400px; Höhe: 1800px;<br /> Zoom: Ausschnitt größtmöglich wählen (100%); Ausrichten an: Fokuspunkt des Bildes |
| **header-1by1** _(1:1 Format - Quadrat)_ |  :responsive&nbsp;images |  |
| | :focuspoint fit | Breite: 2400px; Höhe: 2400px<br /> Zoom: Ausschnitt größtmöglich wählen (100%); Ausrichten an: Fokuspunkt des Bildes |



**Eingabe**

```php
$media = Media::get(REX_MEDIA[1]);
echo $media
        ->setMediaType('header-1by1')
        ->addPictureSource('(min-width: 1200px)', '50vw', 'header-16by9')
        ->addPictureSource('(min-width: 992px)', '70vw', 'header-16by9')
        ->addPictureSource('(min-width: 768px)', '80vw', 'header-4by3')
        ->toPicture();
```

**Ausgabe**

```html
<picture>
    <source media="(min-width: 1200px)" sizes="50vw"
        srcset="/images/header-16by9/200/semperoper.jpg 200w,
                /images/header-16by9/400/semperoper.jpg 400w,
                /images/header-16by9/800/semperoper.jpg 800w,
                /images/header-16by9/1200/semperoper.jpg 1200w,
                /images/header-16by9/1600/semperoper.jpg 1600w,
                /images/header-16by9/2000/semperoper.jpg 2000w">
    <source media="(min-width: 992px)" sizes="70vw"
        srcset="/images/header-16by9/200/semperoper.jpg 200w,
                /images/header-16by9/400/semperoper.jpg 400w,
                /images/header-16by9/800/semperoper.jpg 800w,
                /images/header-16by9/1200/semperoper.jpg 1200w,
                /images/header-16by9/1600/semperoper.jpg 1600w,
                /images/header-16by9/2000/semperoper.jpg 2000w">
    <source media="(min-width: 768px)" sizes="80vw"
        srcset="/images/header-4by3/200/semperoper.jpg 200w,
                /images/header-4by3/400/semperoper.jpg 400w,
                /images/header-4by3/800/semperoper.jpg 800w,
                /images/header-4by3/1200/semperoper.jpg 1200w,
                /images/header-4by3/1600/semperoper.jpg 1600w,
                /images/header-4by3/2000/semperoper.jpg 2000w">
    <img src="/images/header-1by1/400/semperoper.jpg" alt="Semperoper" title="Semperoper"
        srcset="/images/header-1by1/200/semperoper.jpg 200w,
                /images/header-1by1/400/semperoper.jpg 400w,
                /images/header-1by1/800/semperoper.jpg 800w,
                /images/header-1by1/1200/semperoper.jpg 1200w,
                /images/header-1by1/1600/semperoper.jpg 1600w,
                /images/header-1by1/2000/semperoper.jpg 2000w">
</picture>
```

**Ausgabe der Bilder**

| Datei| Bildgröße in px | Verhältnis |
| ------------- | ------------- | ------------- |
| /images/`header-1by1/400`/semperoper.jpg 400w | 400 x 400 | 1:1 |
| /images/`header-4by3/800`/semperoper.jpg 800w | 800 x 600 | 4:3 |
| /images/`header-16by9/1200`/semperoper.jpg 1200w | 1200 x 675 | 16:9 |



## Content Sections

```html
<section class="blue-background">
    >>> Modul Headline
    >>> Modul Text
    >>> Modul Bild
</section>
<section class="white-background">
    >>> Modul Bild
    >>> Modul Headline
    >>> Modul Text
    >>> Modul Text
</section>
<section class="image-background" style="background-image: url('/media/pattern.png');">
    >>> Modul Headline
    >>> Modul Bild
    >>> Modul Text
</section>
```

**Problem**
Man erkennt sofort das Problem. Wie definiert man diese Section um die verschiedenen Module.

**Lösung**
Vor jeder Sektion wird im Artikel ein Slice angelegt. In diesem Slice kann die Hintergrundfarbe oder ähnliches definiert werden. 

### Beispiel Modul

**Moduleingabe**

```php
<fieldset class="form-horizontal">
    <div class="form-group">
        <div class="col-md-2"><label class="control-label">Hintergrund</label></div>
        <div class="col-md-10">
            <div class="rex-select-style">
            <?php

            use Project\Theme;

            $s = new rex_select();
            $s->setId('change-select');
            $s->setName('REX_INPUT_VALUE[1]');
            $s->setSelected('REX_VALUE[1]');
            $s->addOptgroup('Allgemeines');
            $s->addOptions([
                'color-default' => 'Weiß',
                'color-alternate' => 'Sand',
                'image' => 'Bild',
            ]);
            $s->addOptgroup('Grafik / Signet');
            $s->addOptions(Theme::getAllAsArray());
            echo $s->get();
            ?>
            </div>
        </div>
    </div>
</fieldset>

<fieldset id="change-target-image" style="display: none">
    <legend>Bild</legend>
    <div class="form-group">
        <label class="col-md-2 control-label">Auswahl</label>
        <div class="col-md-10">
            REX_MEDIA[id=1 widget=1]
        </div>
    </div>
</fieldset>

<script type="text/javascript">
(function($) {
    var currentShown = null;
    $("#change-select").change(function(){
        if(currentShown) currentShown.hide().find(":input").prop("disabled", true);
        var tableParamsId = "#change-target-"+ jQuery(this).val();
        currentShown = $(tableParamsId);
        currentShown.show().find(":input").prop("disabled", false);
    }).change();
})(jQuery);
</script>
```

**Modulausgabe**

```php
<?php
use Project\Theme;
use Yakme\Html\SectionCreator;
use Yakme\Svg;

// Änderung auch in der Eingabe vornehmen
$options = [
    'color-default' => 'Weiß',
    'color-alternate' => 'Sand',
    'image' => 'Bild',
];

$themes = Theme::getAllAsArray();
$value = 'REX_VALUE[1]';


// wird in der Page.php in Html Sections umgewandelt
if (rex::isBackend()) {
    $backendOptions = array_merge($options, $themes);
    echo rex_view::info('<p class="text-center"><small>Neue Farbsektion ab hier</small><br /><b>' . $backendOptions[$value] .'</b></p>');
} else {
    if (isset($themes[$value])) {
        $section = new SectionCreator('DECOR');
        $section->addOption('prependHtml', '<div class="decor" data-theme="' . $value . '">' . Svg::get('wallpaper') .'</div>');
        echo $section->getPlaceholder();
    } elseif ($value == 'image') {
        $media = Media::get('REX_MEDIA[1]');
        if ($media) {
            $section = new SectionCreator('IMAGE');
            $section->addOption('attributes', 'style="background-image: url(' . $media->getUrl() . ');"');
            echo $section->getPlaceholder();
        }
    } else {
        $section = new SectionCreator(strtoupper(str_replace('color-', '', $value)));
        echo $section->getPlaceholder();
    }
}
```

In der Modulausgabe werden Platzhalter erstellt, die vor der Ausgabe des Inhaltes in Sektionen umgewandelt werden.

**Beispiele erstellter Platzhalter**

```
{{{ HTML_SECTION__DECOR|options(prependHtml::=>::"<div class=\"decor\" data-theme=\"computer\"><svg aria-hidden=\"true\"><use xlink:href=\"#wallpaper\"><\/use><\/svg><\/div>") }}}

>>> Modul Headline
>>> Modul Text
>>> Modul Bild

{{{ HTML_SECTION__IMAGE|options(attributes::=>::"style=\"background-image: url(\/media\/pattern.png);\"") }}}

>>> Modul Bild
>>> Modul Text

{{{ HTML_SECTION__ALTERNATE }}}

>>> Modul Text
>>> Modul Text
```


Damit die Umwandlung in Sektionen passieren kann, muss der folgende Extension Point vor der Ausgabe des Inhaltes registriert werden.

```php
$content = $this->getArticle(1);
$content = \rex_extension::registerPoint(new \rex_extension_point('YAKME_OUTPUT_ARTICLE_CONTENT', $content));
return '<main>' . $content . '</main>';
```

**Beispiele nach der Ersetzung der Platzhalter**

```php
// Aus
// {{{ HTML_SECTION__DECOR|options(prependHtml::=>::"<div class=\"decor\" data-theme=\"computer\"><svg aria-hidden=\"true\"><use xlink:href=\"#wallpaper\"><\/use><\/svg><\/div>") }}}
// wird
<section class="content-section" data-section="decor">
    <div class="decor" data-theme="computer">
        <svg aria-hidden="true"><use xlink:href="#wallpaper"></use></svg>
    </div>
    <div class="content-container">
        >>> Modul Headline
        >>> Modul Text
        >>> Modul Bild
    </div>
</section>


// Aus
// {{{ HTML_SECTION__IMAGE|options(attributes::=>::"style=\"background-image: url(\/media\/pattern.png);\"") }}}
// wird
<section class="content-section" data-section="image" style="background-image: url(/media/pattern.png);">
    <div class="content-container">
        >>> Modul Bild
        >>> Modul Text
    </div>
</section>

// Aus
// {{{ HTML_SECTION__ALTERNATE }}}
// wird
<section class="content-section" data-section="alternate">
    <div class="content-container">
        >>> Modul Text
        >>> Modul Text
    </div>
</section>

```


## Media

### Download


**Voraussetzungen**

`.htaccess` öffnen und ergännzen

```
RewriteRule ^download/([^/]*) %{ENV:BASE}/index.php?download_file=$1&%{QUERY_STRING} [B]
```

**Beispiel**

```php
$media = Media::get('REX_MEDIA[1]');
if ($media) {
    echo sprintf('<a href="%s">Download</a>', $media->getDownloadUrl());
}

```