<?php
/**
 * Textile Addon.
 *
 * @author markus[dot]staab[at]redaxo[dot]de Markus Staab
 *
 * @package redaxo4
 *
 * @version svn:$Id$
 */

function rex_texile_helper()
{
    $formats = rex_a79_help_overview_formats();

    echo '<h3>Anleitung/Hinweise</h3><div class="panel-group" id="textile-helper-accordion">';

    foreach ($formats as $format) {
        $label = $format[0];
        $id = preg_replace('/[^a-zA-z0-9]/', '', htmlentities($label));

        echo '
        <div class="panel panel-default">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#textile-helper-accordion" href="#textile-helper-' . $id . '">
                        ' . htmlspecialchars($label) . '
                    </a>
                </h4>
            </div>
            <div class="panel-collapse collapse" id="textile-helper-' . $id . '">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Eingabe</th>
                            <th>Vorschau</th>
                        </tr>
                    </thead>
                    <tbody>
        ';

        foreach ($format[1] as $perm => $formats) {
            foreach ($formats as $_format) {
                $desc = $_format[0];

                $code = '';
                if (isset($_format[1])) {
                    $code = $_format[1];
                }

                if ($code == '') {
                    $code = $desc;
                }

                $code = trim(convertMarkup($code));

                echo '
                    <tr>
                        <td>' . nl2br(htmlspecialchars($desc)) . '</td>
                        <td>' . $code . '</td>
                    </tr>';
            }
        }

        echo '      </tbody>
                </table>
            </div>
        </div>';
    }
    echo '</div>';
}

function rex_a79_help_overview_formats()
{
    return [
        rex_a79_help_headlines(),
        rex_a79_help_formats(),
        rex_a79_help_links(),
        rex_a79_help_footnotes(),
        rex_a79_help_lists(),
        rex_a79_help_tables(),
    ];
}

function rex_a79_help_headlines()
{
    return [
        'Überschriften',
        [
            'headlines1-3' => [
                    ['h1. Überschrift 1'],
                    ['h2. Überschrift 2'],
                    ['h3. Überschrift 3'],
                ],
            'headlines4-6' => [
                    ['h4. Überschrift 4'],
                    ['h5. Überschrift 5'],
                    ['h6. Überschrift 6'],
                ],
        ],
    ];
}

function rex_a79_help_formats()
{
    return [
        'Textformatierungen',
        [
            'text_xhtml' => [
                    ['_kursiv_'],
                    ['*fett*'],
                ],
            'text_html' => [
                    ['__kursiv__'],
                    ['**fett**'],
                ],
                'cite' => [
                    ['bq. Zitat'],
                    ['??Quelle/Autor??'],
                ],
                'overwork' => [
                    ['-durchgestrichen-'],
                    ['+eingefügt+'],
                    ['^hochgestellt^'],
                    ['~tiefgestellt~'],
                ],
                'code' => [
                    ['@ <?php echo "Hi"; ?>@'],
                ],
        ],
    ];
}

function rex_a79_help_links()
{
    return [
        'Links/Anker',
        [
        'links_intern' => [
            ['Link (intern): zum "Impressum":redaxo://5'],
            ['Link (intern) mit Anker: "zu unseren AGBs":redaxo://7#AGB'],
        ],
        'links_extern' => [
            ['Link (extern): "zur REDAXO Dokumentation":http://doku.redaxo.de'],
            ['Link (extern) mit Anker: "zu unserem Parnter":http://www..redaxo.de#news'],
        ],
        'links_attributes' => [
            ['Link mit title-Attribut: "Bild(Klick für Zoom)":/media/test.jpg'],
            ['Link mit rel-Attribut: "Bild{shadowbox}":/media/test.jpg'],
            ['Link mit title und rel-Attribut: "Bild(Klick für Shadowbox){shadowbox}":/media/test.jpg'],
        ],
        'anchor' => [
            ["Anker definieren:\n\np(#Impressum). Hier steht das Impressum"],
        ],
    ],
  ];
}

function rex_a79_help_footnotes()
{
    return [
'Fußnoten',
[
'footnotes' => [
['AJAX[1] ..'],
['fn1. Asynchronous JavaScript and XML'],
],
],
];
}

function rex_a79_help_lists()
{
    return [
'Listen',
[
'lists' => [
["Nummerierte-Liste:\n# redaxo.de\n# forum.redaxo.de"],
["Aufzählungs-Liste:\n* redaxo.de\n* forum.redaxo.de"],
],
],
];
}

function rex_a79_help_tables()
{
    return [
'Tabellen',
[
'tables' => [
["|_. Id|_. Name|\n|1|Peter|"],
["|www.redaxo.de|35|\n|doku.redaxo.de|32|\n|wiki.redaxo.de|12|"],
],
],
];
}
