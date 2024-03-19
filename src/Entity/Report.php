<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Entity;

use DateTime;
use Odiseo\SyliusReportPlugin\DataFetcher\DefaultDataFetchers;
use Odiseo\SyliusReportPlugin\DataFetcher\TimePeriodDataFetcher;
use Odiseo\SyliusReportPlugin\Renderer\DefaultRenderers;
use Sylius\Component\Resource\Model\TimestampableTrait;

class Report implements ReportInterface
{
    use TimestampableTrait;

    protected ?int $id = null;

    protected ?string $code = null;

    protected ?string $name = null;

    protected ?string $description = null;

    protected string $renderer = DefaultRenderers::TABLE;

    protected array $rendererConfiguration = [];

    protected string $dataFetcher = DefaultDataFetchers::USER_REGISTRATION;

    protected array $dataFetcherConfiguration = [];

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->dataFetcherConfiguration = [
            'timePeriod' => [
                'start' => new DateTime('10 years'),
                'end' => new DateTime(),
                'period' => TimePeriodDataFetcher::PERIOD_MONTH,
            ],
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDataFetcher(): string
    {
        return $this->dataFetcher;
    }

    public function setDataFetcher(string $dataFetcher): void
    {
        $this->dataFetcher = $dataFetcher;
    }

    public function getRenderer(): string
    {
        return $this->renderer;
    }

    public function setRenderer(string $renderer): void
    {
        $this->renderer = $renderer;
    }

    public function getDataFetcherConfiguration(): array
    {
        return $this->dataFetcherConfiguration;
    }

    public function setDataFetcherConfiguration(array $dataFetcherConfiguration): void
    {
        $this->dataFetcherConfiguration = $dataFetcherConfiguration;
    }

    public function getRendererConfiguration(): array
    {
        return $this->rendererConfiguration;
    }

    public function setRendererConfiguration(array $rendererConfiguration): void
    {
        $this->rendererConfiguration = $rendererConfiguration;
    }
}
