Yakme AddOn
================================================================================

## Responsive images

### Voraussetzungen

#### `.htaccess` öffnen und ergänzen

```
# -yakme- = Separator for responsive images
RewriteRule ^images/([^/]*)/([^/]*)/([^/]*) %{ENV:BASE}/index.php?rex_media_type=$1&rex_media_file=$3-yakme-$2&%{QUERY_STRING} [B]
```

#### Breakpoints definieren

Die `package.yml` des AddOns `project` öffnen und einfügen

```
breakpoints:
    sm: 769px
    md: 1024px
    lg: 1216px
    xl: 1408px
    xxl: 1600px
```

> Achtung: Die obenstehenden Breakpoints sollten mit der Vorgabe aus dem Sass übereinstimmen.


#### Folgende Mediatypen anlegen

| Mediatyp | Effekte | Hinweise |
| ------------- | ------------- | ------------- |
| **16by9** _(16:9 Format)_ | :responsive&nbsp;images |  |
|  | :focuspoint fit | Breite: 2400px; Höhe: 1350px;<br /> Zoom-Faktor: 100% (Ausschnitt größtmöglich wählen) |
| **4by3** _(4:3 Format)_ | :responsive&nbsp;images |  |
| |  :focuspoint fit | Breite: 2400px; Höhe: 1800px;<br /> Zoom-Faktor: 100% (Ausschnitt größtmöglich wählen) |
| **1by1** _(1:1 Format - Quadrat)_ |  :responsive&nbsp;images |  |
| | :focuspoint fit | Breite: 2400px; Höhe: 2400px<br /> Zoom-Faktor: 100% (Ausschnitt größtmöglich wählen) |



### Beispiele


**Eingabe**

```php
$media = Media::get('REX_MEDIA[1]');
if (!$media) {
    return false;
}
$image = $media->setMediaType('1by1')
    ->addPictureSource(MediaQuery::from('xl'), '100vw', '16by9')
    ->addPictureSource(MediaQuery::from('sm'), '100vw', '4by3')
    ->usePicture()
    ->toFigure(['class' => ['media']]);
```

**Ausgabe**

```html
<figure class="media">
    <picture>
        <source media="(min-width: 1408px)" sizes="100vw" 
            srcset="/images/21by9/200/stage.jpg 200w,
                    /images/21by9/400/stage.jpg 400w,
                    /images/21by9/800/stage.jpg 800w,
                    /images/21by9/1200/stage.jpg 1200w,
                    /images/21by9/1600/stage.jpg 1600w,
                    /images/21by9/2000/stage.jpg 2000w,
                    /images/21by9/2400/stage.jpg 2400w">
        <source media="(min-width: 769px)" sizes="100vw" 
            srcset="/images/2by1/200/stage.jpg 200w,
                    /images/2by1/400/stage.jpg 400w,
                    /images/2by1/800/stage.jpg 800w,
                    /images/2by1/1200/stage.jpg 1200w,
                    /images/2by1/1600/stage.jpg 1600w,
                    /images/2by1/2000/stage.jpg 2000w,
                    /images/2by1/2400/stage.jpg 2400w">
        <img src="/images/3by2/400/stage.jpg" alt="Bühnenbild" title="Bühnenbild" width="100%" 
            srcset="/images/3by2/200/stage.jpg 200w,
                    /images/3by2/400/stage.jpg 400w,
                    /images/3by2/800/stage.jpg 800w,
                    /images/3by2/1200/stage.jpg 1200w,
                    /images/3by2/1600/stage.jpg 1600w,
                    /images/3by2/2000/stage.jpg 2000w,
                    /images/3by2/2400/stage.jpg 2400w" sizes="">
    </picture>
</figure>
```

**Ausgabe der Bilder**

| Datei| Bildgröße in px | Verhältnis |
| ------------- | ------------- | ------------- |
| /images/`1by1/400`/stage.jpg 400w | 400 x 400 | 1:1 |
| /images/`4by3/800`/stage.jpg 800w | 800 x 600 | 4:3 |
| /images/`16by9/1200`/stage.jpg 1200w | 1200 x 675 | 16:9 |





## VERALTET


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


## MetaInfos

### auf Ebenen einschränken


Feld `legend` mit den Feldattributen `data-hide-levels data-show-level="1"` anlegen.
Damit sagt man, dass alles was in diesem `fieldset` liegt, nur auf der **ersten Ebene** angezeigt wird.

**Wichtig:** 
Ganz am Ende ein Feld `legend` anlegen damit der Button `Metadaten aktualisieren` nicht verschwindet sobald die MetaInfos versteckt werden.


## YForm

Sobald Medien, Katgorien oder Artikel gelöscht werden, prüft Yakme auf deren Verknüpfungen und unterbinded ggf. das Löschen.

**Diese Feldertypen werden automatisch geprüft**
- be_media
- be_link
- be_select_category
- mediafile

Es kann jedoch vorkommen, dass eigene Felder ebenfalls Daten einer Kategorie-Id, Artikel-Id oder eines Mediums enthalten. Ein Bspl. wäre ein normales `select`, welches bestimmte Kategorien zur Auswahl enthält.

Hierfür stehen 2 ExtensionPoints zur Verfügung

- `YFORM_ARTICLE_IS_IN_USE`
- `YFORM_MEDIA_IS_IN_USE`

```php
// Spezielle YForm-Values prüfen, sobald Katgorie oder Artikel gelöscht werden.
\rex_extension::register('YFORM_ARTICLE_IS_IN_USE', function(\rex_extension_point $ep) {
    $fields = [
        [
            'table_name' => \rex::getTable('table'),
            'name' => 'category_id',
            'multiple' => '0',
        ]
    ];
    return array_merge($ep->getSubject(), $fields);
});
``
