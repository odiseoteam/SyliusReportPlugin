<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Controller\Action;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\ViewHandlerInterface;
use Odiseo\SyliusReportPlugin\Repository\AddressRepositoryInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PostcodeSearchAction
{
    /** @var AddressRepositoryInterface */
    private $addressRepository;

    /** @var ViewHandlerInterface */
    private $viewHandler;

    public function __construct(
        AddressRepositoryInterface $addressRepository,
        ViewHandler $viewHandler
    )
    {
        $this->addressRepository = $addressRepository;
        $this->viewHandler = $viewHandler;
    }

    public function __invoke(Request $request): Response
    {
        $addresses = $this->getAddresses($request->get('postcode', ''));
        $view = View::create($addresses);

        $this->viewHandler->setExclusionStrategyGroups(['Autocomplete']);
        $view->getContext()->enableMaxDepth();

        return $this->viewHandler->handle($view);
    }

    private function getAddresses($query): array
    {
        $addresses = [];
        $searchAddresses = $this->addressRepository->findByPostcode($query);

        /** @var AddressInterface $address */
        foreach ($searchAddresses as $address) {
            $postcodeLabel = $address->getPostcode().', '.$address->getCountryCode();
            $isNew = count(array_filter($addresses, function ($address) use ($postcodeLabel) {
                return $address['postcode'] === $postcodeLabel;
            })) === 0;

            if ($isNew) {
                $addresses[] = [
                    'postcode' => $postcodeLabel,
                    'id' => $address->getId(),
                ];
            }
        }

        return $addresses;
    }
}
