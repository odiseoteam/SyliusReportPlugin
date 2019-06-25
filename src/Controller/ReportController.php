<?php

namespace Odiseo\SyliusReportPlugin\Controller;

use FOS\RestBundle\View\View;
use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\DataFetcher\DataFetcherInterface;
use Odiseo\SyliusReportPlugin\DataFetcher\DelegatingDataFetcherInterface;
use Odiseo\SyliusReportPlugin\Model\ReportInterface;
use Odiseo\SyliusReportPlugin\Renderer\DelegatingRendererInterface;
use Odiseo\SyliusReportPlugin\Response\CsvResponse;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Fernando Caraballo Ortiz <caraballo.ortiz@gmail.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class ReportController extends ResourceController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function renderAction(Request $request)
    {
        /** @var FormFactoryInterface $formFactory */
        $formFactory = $this->container->get('form.factory');
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);

        /** @var ReportInterface $report */
        $report = $this->findOr404($configuration);

        /** @var DataFetcherInterface $dataFetcher */
        $dataFetcher = $this->getReportDataFetcher()->getDataFetcher($report);
        /** @var FormInterface $configurationForm */
        $configurationForm = $formFactory->createNamed(
            'configuration',
            $dataFetcher->getType(),
            $report->getDataFetcherConfiguration()
        );

        if ($request->query->has('configuration')) {
            $configurationForm->handleRequest($request);
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
     *
     * @return Response
     */
    public function exportAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);

        /** @var ReportInterface $report */
        $report = $this->findOr404($configuration);

        $type = $request->get('type');
        $configuration = $report->getDataFetcherConfiguration();

        /** @var CurrencyContextInterface $currencyContext */
        $currencyContext = $this->get('sylius.context.currency');

        $configuration['baseCurrency'] = $currencyContext->getCurrencyCode();

        /** @var Data $data */
        $data = $this->getReportDataFetcher()->fetch($report, $configuration);

        $response = null;
        switch ($type) {
            case 'json':
                $response = $this->createJsonResponse($data);
                break;
            case 'csv':
            default:
                $response = $this->createCsvResponse($data);
                break;
        }

        return $response;
    }

    /**
     * @param ReportInterface $report
     * @param array $configuration
     *
     * @return Response
     */
    public function embedAction(ReportInterface $report, array $configuration = [])
    {
        /** @var CurrencyContextInterface $currencyContext */
        $currencyContext = $this->get('sylius.context.currency');

        $configuration = (count($configuration) > 0) ? $configuration : $report->getDataFetcherConfiguration();
        $configuration['baseCurrency'] = $currencyContext->getCurrencyCode();

        $data = $this->getReportDataFetcher()->fetch($report, $configuration);

        return new Response($this->getReportRenderer()->render($report, $data));
    }

    /**
     * @return DelegatingRendererInterface
     */
    private function getReportRenderer()
    {
        /** @var DelegatingRendererInterface $renderer */
        $renderer = $this->container->get('odiseo_sylius_report.renderer');

        return $renderer;
    }

    /**
     * @return DelegatingDataFetcherInterface
     */
    private function getReportDataFetcher()
    {
        /** @var DelegatingDataFetcherInterface $dataFetcher */
        $dataFetcher = $this->container->get('odiseo_sylius_report.data_fetcher');

        return $dataFetcher;
    }

    /**
     * @param Data $data
     *
     * @return Response
     */
    protected function createJsonResponse($data)
    {
        $responseData = [];
        foreach ($data->getData() as $key => $value) {
            $responseData[] = [$key, $value];
        }

        $response = JsonResponse::create($responseData);

        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', 'export.json'));

        if (!$response->headers->has('Content-Type')) {
            $response->headers->set('Content-Type', 'text/json');
        }

        return $response;
    }

    /**
     * @param Data $data
     *
     * @return Response
     */
    protected function createCsvResponse($data)
    {
        $labels = [$data->getLabels()];

        $responseData = array_merge($labels, $data->getData());

        return new CsvResponse($responseData);
    }
}
