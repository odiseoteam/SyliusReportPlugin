<?php

namespace Odiseo\SyliusReportPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class AdminMenuListener
{
    /**
     * @param MenuBuilderEvent $event
     */
    public function addAdminMenuItems(MenuBuilderEvent $event)
    {
        $menu = $event->getMenu();

        $menu->getChild('marketing')
            ->addChild('reports', ['route' => 'odiseo_sylius_report_admin_report_index'])
            ->setLabel('odiseo_sylius_report.ui.reports')
            ->setLabelAttribute('icon', 'bar chart')
        ;
    }
}