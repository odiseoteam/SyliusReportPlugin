<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusReportPlugin\Behat\Page\Admin\Report;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;

interface ShowPageInterface extends SymfonyPageInterface
{
    /**
     * @return string
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getHeaderTitle(): string;
}
