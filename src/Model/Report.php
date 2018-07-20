<?php

namespace Odiseo\SyliusReportPlugin\Model;

use Odiseo\SyliusReportPlugin\DataFetcher\DefaultDataFetchers;
use Odiseo\SyliusReportPlugin\Renderer\DefaultRenderers;
use Sylius\Component\Resource\Model\TimestampableTrait;

class Report implements ReportInterface
{
    use TimestampableTrait;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
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
    public function setDataFetcher($dataFetcher)
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
    public function setRenderer($renderer)
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
