<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusReportPlugin\Behat\Page\Admin\Report;

use Sylius\Behat\Page\Admin\Crud\UpdatePage as BaseUpdatePage;
use Tests\Odiseo\SyliusReportPlugin\Behat\Behaviour\ContainsErrorTrait;

final class UpdatePage extends BaseUpdatePage implements UpdatePageInterface
{
    use ContainsErrorTrait;

    /**
     * @inheritdoc
     */
    public function fillCode(string $code): void
    {
        $this->getDocument()->fillField('Code', $code);
    }

    /**
     * @inheritdoc
     */
    public function fillName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    /**
     * @inheritdoc
     */
    public function fillDescription(string $description): void
    {
        $this->getDocument()->fillField('Description', $description);
    }
}
