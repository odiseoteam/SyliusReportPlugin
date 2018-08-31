<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Odiseo\SyliusReportPlugin\Behat\Page\Admin\Report;

use Sylius\Behat\Page\PageInterface;

interface ShowPageInterface extends PageInterface
{
    /**
     * @return string
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getReportCode();

    /**
     * @return string
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getReportName();

    /**
     * @return string
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getReportDescription();
}
