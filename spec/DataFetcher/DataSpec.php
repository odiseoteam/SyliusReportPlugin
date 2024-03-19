<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use PhpSpec\ObjectBehavior;

class DataSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Data::class);
    }

    function its_labels_is_mutable(): void
    {
        $this->getLabels()->shouldReturn([]);

        $this->setLabels(['labelA', 'labelB']);
        $this->getLabels()->shouldReturn(['labelA', 'labelB']);
    }

    function its_data_is_mutable(): void
    {
        $this->getData()->shouldReturn([]);

        $this->setData(['14', '10']);
        $this->getData()->shouldReturn(['14', '10']);
    }
}
