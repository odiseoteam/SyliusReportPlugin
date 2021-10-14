<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusReportPlugin\Behat\Page\Admin\Report;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

final class ShowPage extends SymfonyPage implements ShowPageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getHeaderTitle(): string
    {
        return $this->getElement('header_title')->getText();
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'odiseo_sylius_report_plugin_admin_report_show';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'header_title' => '#wrapper .header .content',
        ]);
    }
}
