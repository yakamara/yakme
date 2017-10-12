Yakme AddOn
================================================================================
### Beispiele

**Voraussetzungen für das Beispiel**

Folgende Mediatypen anlegen

| Mediatyp | Effekte | Hinweise |
| ------------- | ------------- | ------------- |
| **header-16by9** _(16:9 Format)_ | FocusPoint resize | Breite: 2400px; Höhe: 1350px; Modus: minimum; Zu klein: enlarge |
|  | crop | Breite: 2400px; Höhe: 1350px |
| **header-4by3** _(4:3 Format)_ | FocusPoint resize | Breite: 2400px; Höhe: 1800px; Modus: minimum; Zu klein: enlarge |
|  | crop | Breite: 2400px; Höhe: 1800px |
| **header-1by1** _(1:1 Format - Quadrat)_ | FocusPoint resize | Breite: 2400px; Höhe: 2400px; Modus: minimum; Zu klein: enlarge |
|  | crop | Breite: 2400px; Höhe: 2400px |



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
        srcset="/images/header-16by9--200/semperoper.jpg 200w,
                /images/header-16by9--400/semperoper.jpg 400w,
                /images/header-16by9--800/semperoper.jpg 800w,
                /images/header-16by9--1200/semperoper.jpg 1200w,
                /images/header-16by9--1600/semperoper.jpg 1600w,
                /images/header-16by9--2000/semperoper.jpg 2000w">
    <source media="(min-width: 992px)" sizes="70vw"
        srcset="/images/header-16by9--200/semperoper.jpg 200w,
                /images/header-16by9--400/semperoper.jpg 400w,
                /images/header-16by9--800/semperoper.jpg 800w,
                /images/header-16by9--1200/semperoper.jpg 1200w,
                /images/header-16by9--1600/semperoper.jpg 1600w,
                /images/header-16by9--2000/semperoper.jpg 2000w">
    <source media="(min-width: 768px)" sizes="80vw"
        srcset="/images/header-4by3--200/semperoper.jpg 200w,
                /images/header-4by3--400/semperoper.jpg 400w,
                /images/header-4by3--800/semperoper.jpg 800w,
                /images/header-4by3--1200/semperoper.jpg 1200w,
                /images/header-4by3--1600/semperoper.jpg 1600w,
                /images/header-4by3--2000/semperoper.jpg 2000w">
    <img src="/images/header-1by1--400/semperoper.jpg" alt="Semperoper" title="Semperoper"
        srcset="/images/header-1by1--200/semperoper.jpg 200w,
                /images/header-1by1--400/semperoper.jpg 400w,
                /images/header-1by1--800/semperoper.jpg 800w,
                /images/header-1by1--1200/semperoper.jpg 1200w,
                /images/header-1by1--1600/semperoper.jpg 1600w,
                /images/header-1by1--2000/semperoper.jpg 2000w">
</picture>
```

**Ausgabe der Bilder**

| Datei| Bildgröße in px | Verhältnis |
| ------------- | ------------- | ------------- |
| /images/`header-1by1--400`/semperoper.jpg 400w | 400 x 400 | 1:1 |
| /images/`header-4by3--800`/semperoper.jpg 800w | 800 x 600 | 4:3 |
| /images/`header-16by9--1200`/semperoper.jpg 1200w | 1200 x 675 | 16:9 |
