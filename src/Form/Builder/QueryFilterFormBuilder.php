<?php

namespace Odiseo\SyliusReportPlugin\Form\Builder;

use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\TimePeriodType;
use Odiseo\SyliusReportPlugin\Form\Type\OrderAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType;
use Sylius\Component\Addressing\Model\ProvinceInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class QueryFilterFormBuilder
{
    /** @var RepositoryInterface */
    protected $addressRepository;

    /** @var RepositoryInterface */
    protected $provinceRepository;

    /** @var TaxonRepositoryInterface */
    protected $taxonRepository;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var ChannelRepositoryInterface */
    protected $channelRepository;

    /** @var UrlGeneratorInterface */
    protected $generator;

    public function __construct(
        RepositoryInterface $addressRepository,
        RepositoryInterface $provinceRepository,
        TaxonRepositoryInterface $taxonRepository,
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository,
        ChannelRepositoryInterface $channelRepository,
        UrlGeneratorInterface $generator
    ) {
        $this->addressRepository = $addressRepository;
        $this->provinceRepository = $provinceRepository;
        $this->taxonRepository = $taxonRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->channelRepository = $channelRepository;
        $this->generator = $generator;
    }

    public function addUserGender(FormBuilderInterface &$builder)
    {
        $builder
            ->add('userGender', ChoiceType::class, [
                'choices'  => [
                    'odiseo.report.male' => 'm',
                    'odiseo.report.female' => 'f',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'odiseo_sylius_report.form.user_gender',
            ])
        ;
    }

    public function addUserInfo(FormBuilderInterface &$builder)
    {
        $builder
            ->add('userInfo', ChoiceType::class, [
                'choices'  => [
                    'odiseo_sylius_report.form.user.name' => 'name',
                    'odiseo_sylius_report.form.user.email' => 'email',
                    'odiseo_sylius_report.form.user.phone' => 'phone',
                    'odiseo_sylius_report.form.user.shipping_address' => 'shipping_address',
                    'odiseo_sylius_report.form.user.billing_address' => 'billing_address',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'label' => 'odiseo_sylius_report.form.user_info',
            ])
        ;
    }

    public function addUserBuyer(FormBuilderInterface &$builder)
    {
        $builder
            ->add('userBuyer', ChoiceType::class, [
                'choices'  => [
                    'odiseo_sylius_report.form.both' => 'both',
                    'odiseo_sylius_report.form.yes' => 'yes',
                    'odiseo_sylius_report.form.no' => 'no',
                ],
                'multiple' => false,
                'required' => true,
                'label' => 'odiseo_sylius_report.form.buy',
            ])
        ;
    }

    public function addOrderNumbers(FormBuilderInterface &$builder)
    {
        $builder
            ->add('orderNumbers', OrderAutocompleteChoiceType::class, [
                'label' => 'odiseo_sylius_report.form.orders',
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function addUserCountry(FormBuilderInterface &$builder, $addressType = 'shipping')
    {
        $builder
            ->add('user'.ucfirst($addressType).'Country', CountryType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'fluid search selection'
                ],
                'multiple' => true,
                'label' => 'odiseo_sylius_report.form.'.$addressType.'_country',
            ])
        ;
    }

    public function addUserCity(FormBuilderInterface $builder, $addressType = 'shipping')
    {
        $builder
            ->add('user'.ucfirst($addressType).'City', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'fluid search selection'
                ],
                'multiple' => true,
                'label' => 'odiseo_sylius_report.form.'.$addressType.'_city',
                //'choices' => $this->buildCityChoices()
            ])
        ;
    }

    public function addUserProvince(FormBuilderInterface $builder, $addressType = 'shipping')
    {
        $builder
            ->add('user'.ucfirst($addressType).'Province', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'fluid search selection'
                ],
                'multiple' => true,
                'label' => 'odiseo_sylius_report.form.'.$addressType.'_province',
                //'choices' => $this->buildProvinceChoices()
            ])
        ;
    }

    public function addUserPostcode(FormBuilderInterface $builder, $addressType = 'shipping')
    {
        $builder
            ->add('user'.ucfirst($addressType).'Postcode', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'fluid search selection',
                ],
                'multiple' => true,
                'label' => 'odiseo_sylius_report.form.'.$addressType.'_postcode',
                //'choices' => $this->buildPostcodeChoices()
            ])
        ;
    }

    public function addTimePeriod(FormBuilderInterface $builder)
    {
        $builder
            ->add('timePeriod', TimePeriodType::class, [])
        ;
    }

    public function addChannel(FormBuilderInterface $builder)
    {
        $builder
            ->add('channel', ChoiceType::class, [
                'attr' => [
                    'class' => 'fluid search selection changeSelects'
                ],
                'label' => 'sylius.ui.channel',
                'required' => false,
                'multiple' => true,
                'choices' => $this->buildChannelsChoices()
            ])
        ;
    }

    public function addProduct(FormBuilderInterface $builder)
    {
        $builder
            ->add('product', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'fluid search selection withAjax',
                    //'data-list-url' => $this->generator->generate('odiseo_report_select_products')
                ],
                'multiple' => true,
                'label' => 'sylius.ui.product',
                //'choices' => $this->buildProductsChoices()
            ])
        ;
    }

    public function addProductCategory(FormBuilderInterface $builder)
    {
        $builder
            ->add('productCategory', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'fluid search selection',
                ],
                'multiple' => true,
                'label' => 'odiseo_sylius_report.form.category',
                'choices' => $this->buildCategoriesChoices()
            ])
        ;
    }

    protected function buildProductsChoices()
    {
        $choices = [];
        $products = $this->productRepository->findAll();

        /** @var ProductInterface $product */
        foreach ($products as $product) {
            $choices[$product->getName()] = $product->getId();
        }

        return $choices;
    }

    /**
     * @return array
     */
    protected function buildChannelsChoices(): array
    {
        $choices = [];
        $channels = $this->channelRepository->findAll();

        /** @var ChannelInterface $channel */
        $choices['odiseo_sylius_report.form.all_channels'] = 0;
        foreach ($channels as $channel) {
            $choices[$channel->getName()] = $channel->getId();
        }

        return $choices;
    }

    protected function buildCategoriesChoices()
    {
        $choices = [];
        $categories = $this->taxonRepository->findChildren('category');

        /** @var TaxonInterface $category */
        foreach ($categories as $category) {
            $choices = $this->addCategoryToChoices($choices, $category);
        }

        return $choices;
    }

    protected function addCategoryToChoices($choices, TaxonInterface $category)
    {
        $choices[$category->getName()] = $category->getId();

        /** @var TaxonInterface $subcategory */
        foreach ($category->getChildren() as $subcategory) {
            $choices = $this->addCategoryToChoices($choices, $subcategory);
        }

        return $choices;
    }

    protected function buildCityChoices()
    {
        $choices = [];
        $addresses = $this->addressRepository->findAll();

        /** @var AddressInterface $address */
        foreach ($addresses as $address) {
            $cityLabel = ucfirst(strtolower($address->getCity())).', '.$address->getCountryCode();

            if (!in_array($cityLabel, $choices)) {
                $choices[$cityLabel] = $address->getCity();
            }
        }

        return $choices;
    }

    protected function buildProvinceChoices()
    {
        $choices = [];
        $addresses = $this->addressRepository->findAll();
        $provinces = $this->provinceRepository->findAll();
        $provincesLabel = [];

        /** @var ProvinceInterface $province */
        foreach ($provinces as $province) {
            if (!isset($provincesLabel[$province->getCode()])) {
                $provincesLabel[$province->getCode()] = $province->getName();
            }
        }

        /** @var AddressInterface $address */
        foreach ($addresses as $address) {
            $provinceLabel = isset($provincesLabel[$address->getProvinceCode()])?ucfirst(strtolower($provincesLabel[$address->getProvinceCode()])).', '.$address->getCountryCode():null;

            if ($provinceLabel && !in_array($provinceLabel, $choices)) {
                $choices[$provinceLabel] = $address->getProvinceCode();
            }
        }

        return $choices;
    }

    protected function buildPostcodeChoices()
    {
        $choices = [];
        $addresses = $this->addressRepository->findAll();

        /** @var AddressInterface $address */
        foreach ($addresses as $address) {
            $postcode = $address->getPostcode();

            if (!in_array($postcode, $choices)) {
                $choices[$postcode] = $postcode;
            }
        }

        return $choices;
    }
}
