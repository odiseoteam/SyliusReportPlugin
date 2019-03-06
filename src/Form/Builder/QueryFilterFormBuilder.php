<?php

namespace Odiseo\SyliusReportPlugin\Form\Builder;

use Odiseo\SyliusReportPlugin\Form\Type\AddressAutocompleteChoiceType;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\TimePeriodType;
use Odiseo\SyliusReportPlugin\Form\Type\OrderAutocompleteChoiceType;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
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
        TaxonRepositoryInterface $taxonRepository,
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository,
        ChannelRepositoryInterface $channelRepository,
        UrlGeneratorInterface $generator
    ) {
        $this->addressRepository = $addressRepository;
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
                'label' => 'odiseo_sylius_report.form.'.$addressType.'_country',
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'fluid search selection'
                ],
            ])
        ;
    }

    public function addUserCity(FormBuilderInterface $builder, $addressType = 'shipping')
    {
        $builder
            ->add('user'.ucfirst($addressType).'City', AddressAutocompleteChoiceType::class, [
                'label' => 'odiseo_sylius_report.form.'.$addressType.'_city',
                'multiple' => true,
                'required' => false,
                'remote_url' => $this->generator->generate('odiseo_sylius_report_plugin_admin_ajax_cities'),
                'choice_name' => 'city',
                'attr' => [
                    'class' => 'sylius-autocomplete',
                ],
            ])
        ;
    }

    public function addUserProvince(FormBuilderInterface $builder, $addressType = 'shipping')
    {
        $builder
            ->add('user'.ucfirst($addressType).'Province', AddressAutocompleteChoiceType::class, [
                'label' => 'odiseo_sylius_report.form.'.$addressType.'_province',
                'multiple' => true,
                'required' => false,
                'remote_url' => $this->generator->generate('odiseo_sylius_report_plugin_admin_ajax_provinces'),
                'choice_name' => 'province',
                'attr' => [
                    'class' => 'sylius-autocomplete',
                ],
            ])
        ;
    }

    public function addUserPostcode(FormBuilderInterface $builder, $addressType = 'shipping')
    {
        $builder
            ->add('user'.ucfirst($addressType).'Postcode', AddressAutocompleteChoiceType::class, [
                'label' => 'odiseo_sylius_report.form.'.$addressType.'_postcode',
                'multiple' => true,
                'required' => false,
                'remote_url' => $this->generator->generate('odiseo_sylius_report_plugin_admin_ajax_postcodes'),
                'choice_name' => 'postcode',
                'attr' => [
                    'class' => 'sylius-autocomplete',
                ],
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
}
