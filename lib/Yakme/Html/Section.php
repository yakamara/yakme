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

class Section
{
    const PREFIX = 'HTML_SECTION__';
    const OPEN_TAG = '{{{';
    const CLOSE_TAG = '}}}';

    protected static $availableOptions = [];

    protected $arguments;
    protected $name;
    protected $options;
    protected $placeholder;
    protected $placeholder_with_tags;

    public function __construct($params)
    {
        $this->placeholder = trim($params['placeholder']);
        $this->placeholder_with_tags = trim($params['placeholder_with_tags']);

        if (isset($params['arguments'])) {
            $this->arguments = trim($params['arguments']);
        }
        if (isset($params['name'])) {
            $this->name = trim($params['name']);
        }
        if (isset($params['options'])) {
            $options = explode('::,::', $params['options']);
            if (count($options)) {
                foreach ($options as $option) {
                    $optionPair = explode('::=>::', $option);
                    $this->options[$optionPair[0]] = json_decode($optionPair[1]);
                }
            }
        }

        return $this;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPlaceholderWithTags()
    {
        return $this->placeholder_with_tags;
    }

    public static function setAvailableOptions($options)
    {
        static::$availableOptions = $options;
    }

    public static function getAvailableOptions()
    {
        return static::$availableOptions;
    }

    /**
     * @param string $content
     *
     * @return null|self[]
     */
    public static function parse($content)
    {
        // preg_match_all('@(?<placeholder_with_tags>' . preg_quote(self::OPEN_TAG) . '\s*(?<placeholder>' . self::PREFIX . '(?<name>[A-Z]+)(\|(?<option>[a-zA-Z]+)\((?<arguments>.*?)\))?)\s*' . preg_quote(self::CLOSE_TAG) . ')@', $content, $matches, PREG_SET_ORDER);
        preg_match_all('@(?<placeholder_with_tags>'.preg_quote(self::OPEN_TAG).'\s*(?<placeholder>'.self::PREFIX.'(?<name>[A-Z]+)(\|options\((?<options>.*?)\))?\|[0-9]{6})\s*'.preg_quote(self::CLOSE_TAG).')@', $content, $matches, PREG_SET_ORDER);
        if (count($matches)) {
            $instances = [];
            foreach ($matches as $match) {
                if (substr($match['placeholder'], 0, strlen(self::PREFIX)) == self::PREFIX) {
                    $instances[] = new self($match);
                }
            }
            return $instances;
        }
        return null;
    }
}
