<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Renderer;

/**
 * Default renderers.
 *
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
final class DefaultRenderers
{
    /**
     * Table renderer
     */
    const TABLE = 'odiseo_sylius_report_renderer_table';

    /**
     * Chart renderer
     */
    const CHART = 'odiseo_sylius_report_renderer_chart';
}
