<?php

namespace Odiseo\SyliusReportPlugin\Model;

use Odiseo\SyliusReportPlugin\DataFetcher\DefaultDataFetchers;
use Odiseo\SyliusReportPlugin\DataFetcher\TimePeriodDataFetcher;
use Odiseo\SyliusReportPlugin\Renderer\DefaultRenderers;
use Sylius\Component\Resource\Model\TimestampableTrait;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Diego D'amico <diego@odiseo.com.ar>
 */
class Report implements ReportInterface
{
    use TimestampableTrait;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * Renderer name.
     *
     * @var string
     */
    protected $renderer = DefaultRenderers::TABLE;

    /**
     * @var array
     */
    protected $rendererConfiguration = [];

    /**
     * Data fetcher name.
     *
     * @var string
     */
    protected $dataFetcher = DefaultDataFetchers::USER_REGISTRATION;

    /**
     * @var array
     */
    protected $dataFetcherConfiguration = [];

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->dataFetcherConfiguration = [
            'timePeriod' => [
                'start' => new \DateTime('10 years'),
                'end' => new \DateTime(),
                'period' => TimePeriodDataFetcher::PERIOD_MONTH
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataFetcher()
    {
        return $this->dataFetcher;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataFetcher(string $dataFetcher)
    {
        $this->dataFetcher = $dataFetcher;
    }

    /**
     * @return string
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function setRenderer(string $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataFetcherConfiguration()
    {
        return $this->dataFetcherConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataFetcherConfiguration(array $dataFetcherConfiguration)
    {
        $this->dataFetcherConfiguration = $dataFetcherConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getRendererConfiguration()
    {
        return $this->rendererConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function setRendererConfiguration(array $rendererConfiguration)
    {
        $this->rendererConfiguration = $rendererConfiguration;
    }
}
