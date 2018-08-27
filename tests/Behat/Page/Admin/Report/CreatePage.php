<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusReportPlugin\Behat\Page\Admin\Report;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Tests\Odiseo\SyliusReportPlugin\Behat\Behaviour\ContainsErrorTrait;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    use ContainsErrorTrait;

    /**
     * @inheritdoc
     */
    public function fillCode($code)
    {
        $this->getDocument()->fillField('Code', $code);
    }

    /**
     * @inheritdoc
     */
    public function fillName($name)
    {
        $this->getDocument()->fillField('Name', $name);
    }

    /**
     * @inheritdoc
     */
    public function fillDescription($description)
    {
        $this->getDocument()->fillField('Description', $description);
    }
}
