<?php

namespace spec\Odiseo\SyliusReportPlugin\DataFetcher;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use PhpSpec\ObjectBehavior;

/**
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class DataSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Data::class);
    }

    function its_labels_is_mutable(): void
    {
        $this->getLabels()->shouldReturn(null);

        $this->setLabels(['labelA', 'labelB']);
        $this->getLabels()->shouldReturn(['labelA', 'labelB']);
    }

    function its_data_is_mutable(): void
    {
        $this->getData()->shouldReturn(null);

        $this->setData(['14', '10']);
        $this->getData()->shouldReturn(['14', '10']);
    }
}
