<?php

namespace Odiseo\SyliusReportPlugin\Model;

use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
interface ReportInterface extends CodeAwareInterface, ResourceInterface, TimestampableInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     */
    public function setDescription($description);

    /**
     * @return string|null
     */
    public function getRenderer();

    /**
     * @param string $renderer
     */
    public function setRenderer($renderer);

    /**
     * @return array
     */
    public function getRendererConfiguration();

    /**
     * @param array $rendererConfiguration
     */
    public function setRendererConfiguration(array $rendererConfiguration);

    /**
     * @return string|null
     */
    public function getDataFetcher();

    /**
     * @param string $dataFetcher
     */
    public function setDataFetcher($dataFetcher);

    /**
     * @return array
     */
    public function getDataFetcherConfiguration();

    /**
     * @param array $dataFetcherConfiguration
     */
    public function setDataFetcherConfiguration(array $dataFetcherConfiguration);
}
