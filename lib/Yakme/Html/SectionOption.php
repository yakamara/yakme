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

abstract class SectionOption
{
    /**
     * Returns the name of the option.
     *
     * @return string
     */
    abstract function name();

    /**
     * Execute the option
     *
     * @param  SectionContainer $sectionContainer
     * @param  string $arguments
     *
     * @return SectionContainer
     */
    abstract function fire(SectionContainer $sectionContainer, $arguments);
}
