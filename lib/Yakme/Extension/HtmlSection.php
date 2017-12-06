<?php declare(strict_types=1);

use Yakme\Html\SectionOption;

/**
 * This file is part of the Yakme package.
 *
 * @author (c) Yakamara Media GmbH & Co. KG
 * @author     Thomas Blum <thomas.blum@yakamara.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Yakme\Extension;

use Yakme\Html\Section;
use Yakme\Html\SectionContainer;

class HtmlSection
{
    public static function parse(\rex_extension_point $ep)
    {
        $options = Section::getAvailableOptions();
        $subject = $ep->getSubject();
        $sections = Section::parse($subject);
        if (count($sections)) {
            $search = [];
            $replace = [];

            foreach ($sections as $section) {
                $sectionContainer = new SectionContainer($section->getName());

                if ($section->getOptions()) {
                    foreach ($section->getOptions() as $optionName => $value) {
                        /* @var $availableOption SectionOption */
                        $availableOption = $options[$optionName];
                        $sectionContainer = $availableOption->fire($sectionContainer, $value);
                    }
                }

                $search[] = $section->getPlaceholderWithTags();
                $replace[] = $sectionContainer->build();
            }

            $subject = str_replace($search, $replace, $subject);
            if (SectionContainer::$isOpened) {
                $subject .= sprintf('</div>%s</section>', SectionContainer::$appendHtml);
            }
        }

        $ep->setSubject($subject);
    }
}
