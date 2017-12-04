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

namespace Yakme\Html\SectionOption;

use Yakme\Html\SectionContainer;
use Yakme\Html\SectionOption;

class PrependHtml extends SectionOption
{
    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return 'prependHtml';
    }

    /**
     * {@inheritdoc}
     */
    public function fire(SectionContainer $sectionContainer, $optionValue)
    {
        $sectionContainer->setHTML($optionValue, SectionContainer::HTML_PREPEND);
        return $sectionContainer;
    }
}
