<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Controller\Action;

use FOS\RestBundle\View\ConfigurableViewHandlerInterface;
use FOS\RestBundle\View\View;
use Odiseo\SyliusReportPlugin\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class OrderSearchAction
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var ConfigurableViewHandlerInterface */
    private $viewHandler;

    public function __construct(OrderRepositoryInterface $orderRepository, ConfigurableViewHandlerInterface $viewHandler)
    {
        $this->orderRepository = $orderRepository;
        $this->viewHandler = $viewHandler;
    }

    public function __invoke(Request $request): Response
    {
        $orders = $this->orderRepository->findByNumberPart($request->get('number', ''));
        $view = View::create($orders);

        $this->viewHandler->setExclusionStrategyGroups(['Autocomplete']);
        $view->getContext()->enableMaxDepth();

        return $this->viewHandler->handle($view);
    }
}
