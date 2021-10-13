<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Controller;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Odiseo\SyliusReportPlugin\DataFetcher\DelegatingDataFetcherInterface;
use Odiseo\SyliusReportPlugin\Entity\ReportInterface;
use Odiseo\SyliusReportPlugin\Renderer\DelegatingRendererInterface;
use Odiseo\SyliusReportPlugin\Response\CsvResponse;
use Odiseo\SyliusReportPlugin\Form\Type\ReportDataFetcherConfigurationType;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
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
    public function renderAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);

        /** @var ReportInterface $report */
        $report = $this->findOr404($configuration);
        $reportDataFetcherConfigurationForm = $this->getReportDataFetcherConfigurationForm($report, $request);
        $report = $reportDataFetcherConfigurationForm->getData();

        $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $report);

        if ($configuration->isHtmlRequest()) {
            return $this->render($configuration->getTemplate(ResourceActions::SHOW . '.html'), [
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $report,
                'form' => $reportDataFetcherConfigurationForm->createView(),
                $this->metadata->getName() => $report,
            ]);
        }

        return $this->createRestView($configuration, $report);
    }

    public function exportAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);

        /** @var ReportInterface $report */
        $report = $this->findOr404($configuration);
        $reportDataFetcherConfigurationForm = $this->getReportDataFetcherConfigurationForm($report, $request);
        $report = $reportDataFetcherConfigurationForm->getData();

        $dataFetcherConfiguration = $report->getDataFetcherConfiguration();

        /** @var Data $data */
        $data = $this->getReportDataFetcher()->fetch($report, $dataFetcherConfiguration);

        $filename = $this->slugify($report->getName());

        $format = $request->get('_format');
        $response = null;
        switch ($format) {
            case 'json':
                $response = $this->createJsonResponse($filename, $data);
                break;
            case 'csv':
            default:
                $response = $this->createCsvResponse($filename, $data);
                break;
        }

        return $response;
    }

    public function embedAction(ReportInterface $report, array $dataFetcherConfiguration = []): Response
    {
        $data = $this->getReportDataFetcher()->fetch($report, $dataFetcherConfiguration);

        return new Response($this->getReportRenderer()->render($report, $data));
    }

    private function getReportRenderer(): DelegatingRendererInterface
    {
        return $this->container->get('odiseo_sylius_report_plugin.renderer');
    }

    private function getReportDataFetcher(): DelegatingDataFetcherInterface
    {
        return $this->container->get('odiseo_sylius_report_plugin.data_fetcher');
    }

    protected function createJsonResponse(string $filename, Data $data): Response
    {
        $responseData = [];
        foreach ($data->getData() as $key => $value) {
            $responseData[] = [$key, $value];
        }

        $response = new JsonResponse($responseData);

        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename.'.json'));

        if (!$response->headers->has('Content-Type')) {
            $response->headers->set('Content-Type', 'text/json');
        }

        return $response;
    }

    protected function createCsvResponse(string $filename, Data $data): Response
    {
        $response = new CsvResponse($data);

        $response->setFilename($filename.'.csv');

        return $response;
    }

    protected function getReportDataFetcherConfigurationForm(ReportInterface $report, Request $request): FormInterface
    {
        /** @var FormFactoryInterface $formFactory */
        $formFactory = $this->container->get('form.factory');

        $configurationForm = $formFactory->create(ReportDataFetcherConfigurationType::class, $report);

        if ($request->query->has($configurationForm->getName())) {
            $configurationForm->handleRequest($request);
        }

        return $configurationForm;
    }

    private function slugify(string $string): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }
}
