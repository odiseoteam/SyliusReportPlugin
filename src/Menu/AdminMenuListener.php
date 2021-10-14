<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        /** @var ItemInterface $item */
        $item = $menu->getChild('marketing');
        if (null == $item) {
            $item = $menu;
        }

        $item
            ->addChild('reports', ['route' => 'odiseo_sylius_report_plugin_admin_report_index'])
            ->setLabel('odiseo_sylius_report_plugin.menu.admin.reports')
            ->setLabelAttribute('icon', 'bar chart')
        ;
    }
}
