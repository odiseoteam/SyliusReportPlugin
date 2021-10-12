<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Form\Builder;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Odiseo Team <team@odiseo.com.ar>
 */
interface QueryFilterFormBuilderInterface
{
    public function addUserGender(FormBuilderInterface &$builder): void;

    public function addUserCountry(FormBuilderInterface &$builder, string $addressType = 'shipping'): void;

    public function addUserCity(FormBuilderInterface $builder, string $addressType = 'shipping'): void;

    public function addUserProvince(FormBuilderInterface $builder, string $addressType = 'shipping'): void;

    public function addUserPostcode(FormBuilderInterface $builder, string $addressType = 'shipping'): void;

    public function addTimePeriod(FormBuilderInterface $builder): void;

    public function addChannel(FormBuilderInterface $builder): void;

    public function addProduct(FormBuilderInterface $builder): void;

    public function addProductCategory(FormBuilderInterface $builder): void;
}
