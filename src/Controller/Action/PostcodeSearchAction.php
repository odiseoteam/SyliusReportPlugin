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
    private AddressRepositoryInterface $addressRepository;
    private RepositoryInterface $countryRepository;
    private ConfigurableViewHandlerInterface $viewHandler;

    public function __construct(
        AddressRepositoryInterface $addressRepository,
        RepositoryInterface $countryRepository,
        ConfigurableViewHandlerInterface $viewHandler
    ) {
        $this->addressRepository = $addressRepository;
        $this->countryRepository = $countryRepository;
        $this->viewHandler = $viewHandler;
    }

    public function __invoke(Request $request): Response
    {
        /** @var string $query */
        $query = $request->query->get('postcode', '');

        $addresses = $this->getAddresses($query);
        $view = View::create($addresses);

        $this->viewHandler->setExclusionStrategyGroups(['Autocomplete']);
        $view->getContext()->enableMaxDepth();

        return $this->viewHandler->handle($view);
    }

    private function getAddresses(string $query): array
    {
        $addresses = [];
        $searchAddresses = $this->addressRepository->findByPostcode($query);

        /** @var AddressInterface $address */
        foreach ($searchAddresses as $address) {
            /** @var CountryInterface|null $country */
            $country = $this->countryRepository->findOneBy([
                'code' => $address->getCountryCode()
            ]);

            /** @var string $countryName */
            $countryName = $country !== null ? $country->getName() : $address->getCountryCode();
            /** @var string $postcode */
            $postcode = $address->getPostcode();

            $postcodeLabel = $postcode . ', ' . $countryName;
            $isNew = count(array_filter($addresses, function ($address) use ($postcodeLabel): bool {
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
