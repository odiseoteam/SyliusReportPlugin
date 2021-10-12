<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menuItem = $menu->getChild('marketing');
        if (!$menuItem) {
            $menuItem = $menu;
        }

        $menuItem
            ->addChild('reports', ['route' => 'odiseo_sylius_report_admin_report_index'])
            ->setLabel('odiseo_sylius_report.ui.reports')
            ->setLabelAttribute('icon', 'bar chart')
        ;
    }
}
