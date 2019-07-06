<?php

namespace Odiseo\SyliusReportPlugin\Form\Builder;

use Odiseo\SyliusReportPlugin\Form\Type\AddressAutocompleteChoiceType;
use Odiseo\SyliusReportPlugin\Form\Type\DataFetcher\TimePeriodType;
use Odiseo\SyliusReportPlugin\Form\Type\ProductAutocompleteChoiceType;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Odiseo Team <team@odiseo.com.ar>
 */
class QueryFilterFormBuilder
{
    /** @var RepositoryInterface */
    protected $addressRepository;

    /** @var TaxonRepositoryInterface */
    protected $taxonRepository;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    /** @var ChannelRepositoryInterface */
    protected $channelRepository;

    /** @var UrlGeneratorInterface */
    protected $generator;

    public function __construct(
        RepositoryInterface $addressRepository,
        TaxonRepositoryInterface $taxonRepository,
        ProductRepositoryInterface $productRepository,
        ChannelRepositoryInterface $channelRepository,
        UrlGeneratorInterface $generator
    ) {
        $this->addressRepository = $addressRepository;
        $this->taxonRepository = $taxonRepository;
        $this->productRepository = $productRepository;
        $this->channelRepository = $channelRepository;
        $this->generator = $generator;
    }

    public function addUserGender(FormBuilderInterface &$builder)
    {
        $builder
            ->add('userGender', ChoiceType::class, [
                'choices'  => [
                    'odiseo_sylius_report.form.user_gender.male' => 'm',
                    'odiseo_sylius_report.form.user_gender.female' => 'f',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'odiseo_sylius_report.form.user_gender.label',
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
            ->add('product', ProductAutocompleteChoiceType::class, [
                'label' => 'sylius.ui.product',
                'multiple' => true,
                'required' => false,
                'remote_url' => $this->generator->generate('odiseo_sylius_report_plugin_admin_ajax_products'),
                'choice_name' => 'name',
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

    /**
     * @return array
     */
    protected function buildChannelsChoices(): array
    {
        $choices = [];
        $channels = $this->channelRepository->findAll();

        $choices['odiseo_sylius_report.form.all_channels'] = 0;

        /** @var ChannelInterface $channel */
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
