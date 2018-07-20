<?php

namespace Odiseo\SyliusReportPlugin\Controller;

use FOS\RestBundle\View\View;
use Odiseo\SyliusReportPlugin\DataFetcher\DataFetcherInterface;
use Odiseo\SyliusReportPlugin\DataFetcher\DelegatingDataFetcherInterface;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use Odiseo\SyliusReportPlugin\Renderer\DelegatingRendererInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends ResourceController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function renderAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);

        /** @var ReportInterface $report */
        $report = $this->findOr404($configuration);

        /** @var DataFetcherInterface $dataFetcher */
        $dataFetcher = $this->getReportDataFetcher()->getDataFetcher($report);
        /** @var FormInterface $configurationForm */
        $configurationForm = $this->container->get('form.factory')->createNamed(
            'configuration',
            $dataFetcher->getType(),
            $report->getDataFetcherConfiguration()
        );

        if ($request->query->has('configuration')) {
            $configurationForm->submit($request);
        }

        $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $report);

        $view = View::create($report);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::SHOW . '.html'))
                ->setTemplateVar($this->metadata->getName())
                ->setData([
                    'configuration' => $configuration,
                    'metadata' => $this->metadata,
                    'resource' => $report,
                    'form' => $configurationForm->createView(),
                    'configurationForm' => $configurationForm->getData(),
                    $this->metadata->getName() => $report,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @param Request $request
     * @param string  $report
     * @param array   $configuration
     *
     * @return Response
     */
    public function embedAction(Request $request, $report, array $configuration = [])
    {
        /** @var CurrencyContextInterface $currencyContext */
        $currencyContext = $this->get('sylius.context.currency');

        if (!$report instanceof ReportInterface) {
            $report = $this->repository->findOneBy(['code' => $report]);
        }

        if (null === $report) {
            return $this->container->get('templating')->renderResponse('@OdiseoSyliusReportPlugin/noDataTemplate.html.twig');
        }

        $configuration = ($request->query->has('configuration')) ? $request->query->get('configuration', $configuration) : $report->getDataFetcherConfiguration();
        $configuration['baseCurrency'] = $currencyContext->getCurrencyCode();

        $data = $this->getReportDataFetcher()->fetch($report, $configuration);

        return new Response($this->getReportRenderer()->render($report, $data));
    }

    /**
     * @return object|DelegatingRendererInterface
     */
    private function getReportRenderer()
    {
        return $this->container->get('odiseo_sylius_report.renderer');
    }

    /**
     * @return object|DelegatingDataFetcherInterface
     */
    private function getReportDataFetcher()
    {
        return $this->container->get('odiseo_sylius_report.data_fetcher');
    }
}
