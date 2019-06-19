<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Controller\Action;

use FOS\RestBundle\View\ConfigurableViewHandlerInterface;
use FOS\RestBundle\View\View;
use Odiseo\SyliusReportPlugin\Repository\AddressRepositoryInterface;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PostcodeSearchAction
{
    /** @var AddressRepositoryInterface */
    private $addressRepository;

    /** @var RepositoryInterface */
    private $countryRepository;

    /** @var ConfigurableViewHandlerInterface */
    private $viewHandler;

    public function __construct(
        AddressRepositoryInterface $addressRepository,
        RepositoryInterface $countryRepository,
        ConfigurableViewHandlerInterface $viewHandler
    )
    {
        $this->addressRepository = $addressRepository;
        $this->countryRepository = $countryRepository;
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
            /** @var CountryInterface $country */
            $country = $this->countryRepository->findOneBy([
                'code' => $address->getCountryCode()
            ]);

            $countryName = $country !== null ? $country->getName() : $address->getCountryCode();

            $postcodeLabel = $address->getPostcode().', '.$countryName;
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
