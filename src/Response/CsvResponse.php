<?php

declare(strict_types=1);

namespace Odiseo\SyliusReportPlugin\Response;

use Odiseo\SyliusReportPlugin\DataFetcher\Data;
use Symfony\Component\HttpFoundation\Response;

class CsvResponse extends Response
{

    protected ?string $data = null;
    protected string $filename = 'export.csv';

    public function __construct(?Data $data = null, int $status = 200, array $headers = [])
    {
        parent::__construct('', $status, $headers);
        $this->setData($data);
    }

    public static function create(?Data $data = null, int $status = 200, array $headers = [])
    {
        return new static($labels, $data, $status, $headers);
    }

    public function setData(Data $data): self
    {
        $output = fopen('php://temp', 'r+');

        if ($output === false) {
            throw new \RuntimeException('Could not create a buffer for CSV output.');
        }

        foreach ($data->getData() as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $this->data = '';

        while ($line = fgets($output)) {
            $this->data .= $line;
        }

        $this->data .= fgets($output);

        return $this->update();
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this->update();
    }

    protected function update(): self
    {
        $this->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $this->filename));
        if (!$this->headers->has('Content-Type')) {
            $this->headers->set('Content-Type', 'text/csv');
        }

        return $this->setContent($this->data);
    }
}
