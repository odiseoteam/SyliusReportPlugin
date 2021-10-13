<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Controller\Action;

use FOS\RestBundle\View\ConfigurableViewHandlerInterface;
use FOS\RestBundle\View\View;
use Odiseo\SyliusReportPlugin\Repository\AddressRepositoryInterface;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Addressing\Model\ProvinceInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Odiseo Team <team@odiseo.com.ar>
 */
final class ProvinceSearchAction
{
    private AddressRepositoryInterface $addressRepository;
    private RepositoryInterface $provinceRepository;
    private RepositoryInterface $countryRepository;
    private ConfigurableViewHandlerInterface $viewHandler;

    public function __construct(
        AddressRepositoryInterface $addressRepository,
        RepositoryInterface $provinceRepository,
        RepositoryInterface $countryRepository,
        ConfigurableViewHandlerInterface $viewHandler
    ) {
        $this->addressRepository = $addressRepository;
        $this->provinceRepository = $provinceRepository;
        $this->countryRepository = $countryRepository;
        $this->viewHandler = $viewHandler;
    }

    public function __invoke(Request $request): Response
    {
        $addresses = $this->getAddresses($request->get('province', ''));
        $view = View::create($addresses);

        $this->viewHandler->setExclusionStrategyGroups(['Autocomplete']);
        $view->getContext()->enableMaxDepth();

        return $this->viewHandler->handle($view);
    }

    private function getAddresses(string $query): array
    {
        $addresses = [];
        $searchAddresses = $this->addressRepository->findByProvinceName($query);

        /** @var AddressInterface $address */
        foreach ($searchAddresses as $address) {
            /** @var CountryInterface|null $country */
            $country = $this->countryRepository->findOneBy([
                'code' => $address->getCountryCode()
            ]);

            $countryName = $country !== null ? $country->getName() : $address->getCountryCode();

            $provinceName = $this->getProvinceName($address);
            $provinceLabel = ucfirst(strtolower($provinceName ?? '')).', '.$countryName;
            $isNew = count(array_filter($addresses, function ($address) use ($provinceLabel): bool {
                return $address['province'] === $provinceLabel;
            })) === 0;

            if ($isNew) {
                $addresses[] = [
                    'province' => $provinceLabel,
                    'id' => $address->getId(),
                ];
            }
        }

        return $addresses;
    }

    private function getProvinceName(AddressInterface $address): ?string
    {
        $provinceName = $address->getProvinceName();

        if (!$provinceName) {
            /** @var ProvinceInterface $province */
            $province = $this->provinceRepository->findOneBy([
                'code' => $address->getProvinceCode(),
            ]);
            $provinceName = $province->getName();
        }

        return $provinceName;
    }
}
