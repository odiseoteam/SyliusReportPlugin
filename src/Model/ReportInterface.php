<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Model;

use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 * @author Rimas Kudelis <rimas.kudelis@adeoweb.biz>
 */
interface ReportInterface extends CodeAwareInterface, ResourceInterface, TimestampableInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    public function getRenderer(): string;

    public function setRenderer(string $renderer): void;

    public function getRendererConfiguration(): array;

    public function setRendererConfiguration(array $rendererConfiguration): void;

    public function getDataFetcher(): string;

    public function setDataFetcher(string $dataFetcher): void;

    public function getDataFetcherConfiguration(): array;

    public function setDataFetcherConfiguration(array $dataFetcherConfiguration): void;
}
