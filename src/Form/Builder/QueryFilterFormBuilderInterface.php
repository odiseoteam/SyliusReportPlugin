<?php

namespace Odiseo\SyliusReportPlugin\Form\Builder;


use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Odiseo Team <team@odiseo.com.ar>
 */
interface QueryFilterFormBuilderInterface
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function addUserGender(FormBuilderInterface &$builder): void;

    /**
     * @param FormBuilderInterface $builder
     * @param string $addressType
     */
    public function addUserCountry(FormBuilderInterface &$builder, $addressType = 'shipping'): void;

    /**
     * @param FormBuilderInterface $builder
     * @param string $addressType
     */
    public function addUserCity(FormBuilderInterface $builder, $addressType = 'shipping'): void;

    /**
     * @param FormBuilderInterface $builder
     * @param string $addressType
     */
    public function addUserProvince(FormBuilderInterface $builder, $addressType = 'shipping'): void;

    /**
     * @param FormBuilderInterface $builder
     * @param string $addressType
     */
    public function addUserPostcode(FormBuilderInterface $builder, $addressType = 'shipping'): void;

    /**
     * @param FormBuilderInterface $builder
     */
    public function addTimePeriod(FormBuilderInterface $builder): void;

    /**
     * @param FormBuilderInterface $builder
     */
    public function addChannel(FormBuilderInterface $builder): void;

    /**
     * @param FormBuilderInterface $builder
     */
    public function addProduct(FormBuilderInterface $builder): void;

    /**
     * @param FormBuilderInterface $builder
     */
    public function addProductCategory(FormBuilderInterface $builder): void;
}
