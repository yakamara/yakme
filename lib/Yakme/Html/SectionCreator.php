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

namespace Yakme\Html;

class SectionCreator
{
    protected $name;
    protected $options = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addOption($optionName, $value)
    {
        $options = Section::getAvailableOptions();

        if (!isset($options[$optionName])) {
            throw new \rex_exception('$optionName is expected to define a subclass of SectionOption, "'.$optionName.'" given!');
        }

        return $this->options[$optionName] = json_encode($value);
    }

    public function getPlaceholder()
    {
        $string = '';
        $string .= Section::OPEN_TAG;
        $string .= ' ';
        $string .= Section::PREFIX;
        $string .= $this->name;

        if (count($this->options)) {
            $options = array_map(function ($optionName, $value) {
                return $optionName.'::=>::'.$value;
            }, array_keys($this->options), array_values($this->options)
            );

            $string .= '|options('.implode('::,::', $options).')';
        }

        $string .= '|'.rand(100, 999).rand(100, 999);
        $string .= ' ';
        $string .= Section::CLOSE_TAG;

        return $string;
    }
}
