Yakme AddOn
================================================================================
### Beispiele

**Voraussetzungen f?r das Beispiel**

Folgende Mediatypen anlegen

| Mediatyp | Effekte | Hinweise |
| ------------- | ------------- | ------------- |
| **header-16by9** _(16:9 Format)_ | FocusPoint resize | Breite: 1600px; Höhe: 900px |
|  | crop | Breite: 1600px; Höhe: 900px |
| **header-4by3** _(4:3 Format)_ | FocusPoint resize | Breite: 1200px; Höhe: 900px |
|  | crop | Breite: 1200px; Höhe: 900px |
| **header-1by1** _(1:1 Format - Quadrat)_ | FocusPoint resize | Breite: 1600px; Höhe: 1600px |
|  | crop | Breite: 1600px; Höhe: 1600px |



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
