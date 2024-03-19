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

final class CitySearchAction
{
    public function __construct(
        private AddressRepositoryInterface $addressRepository,
        private RepositoryInterface $countryRepository,
        private ConfigurableViewHandlerInterface $viewHandler,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var string $query */
        $query = $request->query->get('city', '');

        $addresses = $this->getAddresses($query);
        $view = View::create($addresses);

        $this->viewHandler->setExclusionStrategyGroups(['Autocomplete']);
        $view->getContext()->enableMaxDepth();

        return $this->viewHandler->handle($view);
    }

    private function getAddresses(string $query): array
    {
        $addresses = [];
        $searchAddresses = $this->addressRepository->findByCityName($query);

        /** @var AddressInterface $address */
        foreach ($searchAddresses as $address) {
            /** @var CountryInterface|null $country */
            $country = $this->countryRepository->findOneBy([
                'code' => $address->getCountryCode(),
            ]);

            /** @var string $countryName */
            $countryName = $country !== null ? $country->getName() : $address->getCountryCode();

            $cityLabel = ucfirst(strtolower($address->getCity() ?? '')) . ', ' . $countryName;
            $isNew = count(array_filter($addresses, function ($address) use ($cityLabel): bool {
                return $address['city'] === $cityLabel;
            })) === 0;

            if ($isNew) {
                $addresses[] = [
                    'city' => $cityLabel,
                    'id' => $address->getId(),
                ];
            }
        }

        return $addresses;
    }
}
