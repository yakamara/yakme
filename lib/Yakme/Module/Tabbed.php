<?php declare(strict_types=1);

/**
 * This file is part of the Application package.
 *
 * @author (c) Yakamara Media GmbH & Co. KG
 * @author Thomas Blum <thomas.blum@yakamara.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yakme\Module;

use Yakme\Str;

class Tabbed
{
    private static $gridOptions = [
            '12',
            '6-6',
            '8-4',
            '4-8',
            '4-4-4',
            '6-3-3',
            '3-6-3',
            '3-3-6',
            '3-3-3-3',
        ];

    private $values;
    private $form;
    private $mblock = false;
    private $mblockOptions = [];
    private $order = [];
    private $grid = [];

    private $prependTabs = [];
    private $appendTabs = [];

    /**
     * Constructor.
     *
     * @param array $values
     */
    public function __construct($values = [])
    {
        $this->setValues($values);
    }

    /**
     * add a prepend tab.
     *
     * @param string $label
     * @param string $id
     */
    public function addAppendTab($label, $id)
    {
        $this->appendTabs[$label] = $id;
    }

    /**
     * add a prepend tab.
     *
     * @param string $label
     * @param string $id
     */
    public function addPrependTab($label, $id)
    {
        $this->prependTabs[$label] = $id;
    }

    /**
     * Set form.
     *
     * @param string $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    public function setMBlock($value = true, array $options = [])
    {
        $this->mblock = $value;
        $this->mblockOptions = $options;
    }

    /**
     * Set sortable tabs.
     *
     * @param string $orderName  REX_INPUT_VALUE[n]
     * @param string $orderValue REX_VALUE[n]
     */
    public function setSortableTabs($orderName, $orderValue)
    {
        $this->order['name'] = $orderName;
        $this->order['value'] = $orderValue;
    }

    /**
     * Set grid.
     *
     * @param string $gridName    REX_INPUT_VALUE[n]
     * @param string $gridValue   REX_VALUE[n]
     * @param array  $gridOptions see static $gridOptions
     */
    public function setGrid($gridName, $gridValue, $gridOptions = [])
    {
        $this->grid['name'] = $gridName;
        $this->grid['value'] = $gridValue;

        if (count($gridOptions) >= 1) {
            self::$gridOptions = $gridOptions;
        }
    }

    /**
     * Set values.
     *
     * @param array $values
     */
    public function setValues(array $values)
    {
        $this->values = $values;
    }

    /**
     * Returns the tabs.
     */
    public function getTabs()
    {
        $tabs = '';
        if (count($this->prependTabs)) {
            foreach ($this->prependTabs as $label => $id) {
                $tabs .= '<li class="yakme-tabbed-locked pull-left"><a href="#'.$id.'" data-toggle="tab"><i>'.$label[1].'</i><span>'.$label.'</span></a></li>';
            }
        }
        if ($this->order['value'] != '') {
            $order = explode(',', $this->order['value']);
            for ($i = 1; $i <= count($this->values); ++$i) {
                $tabs .= '<li data-id="'.($order[($i - 1)]).'" ><a id="tab-'.($order[($i - 1)]).'" href="#tab-content-'.($order[($i - 1)]).'" data-toggle="tab"><i>B'.$i.'</i><span>Bereich '.($order[($i - 1)]).'</span></a></li>';
            }
        } else {
            for ($i = 1; $i <= count($this->values); ++$i) {
                $tabs .= '<li data-id="'.$i.'"><a id="tab-'.$i.'" href="#tab-content-'.$i.'" data-toggle="tab"><i>B'.$i.'</i><span>Bereich '.$i.'</span></a></li>';
            }
        }
        if (count($this->appendTabs)) {
            foreach ($this->appendTabs as $label => $id) {
                $tabs .= '<li class="yakme-tabbed-locked"><a href="#'.$id.'" data-toggle="tab"><i>'.$label[1].'</i><span>'.$label.'</span></a></li>';
            }
        }

        $orderInput = (count($this->order) == 2) ? '<input class="yakme-tabbed-order" type="hidden" name="'.$this->order['name'].'" value="'.$this->order['value'].'" />' : '';
        $gridTab = (count($this->grid) == 2) ? '<li class="yakme-tabbed-locked pull-right"><a id="yakme-tabbed-settings" href="#tab-content-settings" data-toggle="tab"><i class="rex-icon rex-icon-metafuncs"></i><span>Einstellungen</span></a></li>' : '';

        return '
        '.$orderInput.'
        <div class="nav rex-page-nav" id="yakme-tabbed-tabs">
            <ul class="nav nav-tabs">
                '.$tabs.'
                '.$gridTab.'
            </ul>
        </div>';
    }

    /**
     * Returns the grid.
     */
    public function getGrid()
    {
        if ((count($this->grid) == 2)) {
            // lösche überflüssige Grid Optionen
            // Bsp. sind 3 Values übergeben ist 3-3-3-3 nicht notwendig
            $valuesCount = count($this->values);
            foreach (self::$gridOptions as $index => $gridOption) {
                $gridOptionColumns = explode('-', $gridOption);
                if (count($gridOptionColumns) > $valuesCount) {
                    unset(self::$gridOptions[$index]);
                }
            }

            $grid = '';
            foreach (self::$gridOptions as $value) {
                $checked = $this->grid['value'] == $value ? ' checked="checked"' : '';
                $grid .= '
                    <label class="yakme-tabbed-grid-item" data-yakme-tabbed-grid="'.$value.'">
                        <input name="'.$this->grid['name'].'" value="'.$value.'" type="radio"'.$checked.' />
                        <span class="yakme-tabbed-grid-item-view"></span>
                    </label>';
            }

            return '
                    <div id="tab-content-settings" class="tab-pane fade in">
                        <fieldset class="form-horizontal">
                            <legend>Raster</legend>
                            <input class="yakme-tabbed-grid-value" type="hidden" value="'.$this->grid['value'].'" />
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Auswahl</label>
                                <div class="col-sm-10 yakme-tabbed-grid">
                                    '.$grid.'
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    ';
        }
        return false;
    }

    /**
     * Returns the javscript and stylesheet files.
     */
    public function getFiles()
    {
        //return false;
        //return '<script src="' . \rex_addon::get('yakme')->getAssetsUrl('modules/tabbed.js') . '"></script>';
                //<link rel="stylesheet" type="text/css" media="all" href="' . rex_addon::get('application')->getAssetsUrl('app_tabbed_module.css') . '" />';
    }

    /**
     * Returns the sorted values and clears values which do not match the grid.
     *
     * @param array  $values
     * @param string $orderValue REX_VALUE[n], same REX_VALUE setSortableTabs
     * @param string $gridValue  REX_VALUE[n], same REX_VALUE setGrid
     *
     * @return array
     */
    public static function getValuesForOutput($values, $orderValue = null, $gridValue = null)
    {
        // Values sortieren
        $order = array_keys($values);
        if (null !== $orderValue && $orderValue != '') {
            $order = explode(',', $orderValue);
        }
        $ordered = [];
        foreach ($order as $key) {
            if (array_key_exists($key, $values)) {
                $ordered[$key] = $values[$key];
                unset($values[$key]);
            }
        }
        $values = $ordered;

        if (null !== $gridValue && $gridValue != '') {
            // nur Values verwenden, die dem Raster entsprechen
            $gridArr = explode('-', $gridValue);
            $values = array_slice($values, 0, count($gridArr));
        }

        return $values;
    }

    public static function manipulateValuesForOutput($values, $widgets = [])
    {
        foreach ($values as $tabIndex => $tabs) {
            foreach ($tabs as $blockIndex => $blockElements) {
                $element = $tabs[$blockIndex]['ELEMENT_SELECT'];
                $values[$tabIndex][$blockIndex]['BLOCK_ELEMENT'] = $element;
                foreach ($blockElements as $key => $value) {
                    if ($key === 'ELEMENT_SELECT') {
                        continue;
                    }
                    if ($key === 'BLOCK_DATA') {
                        continue;
                    }

                    if (Str::startsWith($key, $element)) {
                        $newKey = str_replace($element.'_', '', $key);
                        $values[$tabIndex][$blockIndex]['BLOCK_DATA'][$newKey] = $value;
                    }
                }

                if (isset($widgets[$element])) {
                    foreach ($widgets[$element] as $widget) {
                        $values[$tabIndex][$blockIndex]['BLOCK_DATA'][$widget] = $values[$tabIndex][$blockIndex][$widget];
                    }
                }
            }
        }

        return $values;
    }

    public function get()
    {
        $return = '';
        $return .= $this->getTabs();

        $return .= '<div class="tab-content">';

        foreach ($this->values as $i => $values) {
            $mblockId = '';

            // $search = [];
            // $replace = [];
            // if ($this->mblock) {
            //     $mblockId = '[0]';
            //     $search = array_map(function ($key) {
            //             return '{{{ '.$key.' }}}';
            //         }, array_keys($values[0])
            //     );
            //     $replace = array_values($values[0]);
            //     unset($values[0]);
            // }
            //
            // $search = array_merge($search, array_map(function ($key) {
            //         return '{{{ '.$key.' }}}';
            //     }, array_keys($values)
            // ));
            // dump($values);
            // $replace = array_merge($replace, array_values($values));

            $form = $this->form;
            $form = str_replace('{{{ ID }}}', $i, $form);
            // $form = preg_replace('@'.preg_quote('{{{').'\s*NAME__(.*?)\s*'.preg_quote('}}}').'@', 'REX_INPUT_VALUE['.$i.']'.$mblockId.'[$1]', $form);
            // $form = preg_replace('@'.preg_quote('{{{').'\s*.*?\s*'.preg_quote('}}}').'@', '', $form);

            if ($this->mblock) {
                $form = \MBlock::show($i, $form, $this->mblockOptions);
            }

            $return .= '
                <div class="tab-pane fade in" id="tab-content-'.$i.'">
                    '.$form.'
                </div>';
        }
        $return .= $this->getGrid();
        $return .= '</div>';

        $return = '<div class="yakme-module yakme-tabbed-module">'.$return.'</div>';

        return $return;
    }
}
