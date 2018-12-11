<?php

namespace App\Reporting\Library;

use App\Reporting\Library\Type\YandexReportType;
use App\Reporting\Presentation\Model\YandexTranslationServiceReport;

class ReportsCollector
{
    /**
     * @var bool $isUpdated
     */
    private $isUpdated = false;
    /**
     * @var ReportInterface[] $reports
     */
    private $reports = [];
    /**
     * ReportsCollector constructor.
     */
    public function __construct()
    {
        $this->reports[(string) YandexReportType::fromValue()] = new YandexTranslationServiceReport();
    }
    /**
     * @param string $type
     * @return ReportInterface
     */
    public function getReport(string $type)
    {
        if (!array_key_exists($type, $this->reports)) {
            $message = sprintf(
                'Unknown report %s. Valid reports are %s',
                $type,
                implode(',', array_keys($this->reports))
            );

            throw new \RuntimeException($message);
        }

        $this->isUpdated = true;

        return $this->reports[$type];
    }
    /**
     * @return bool
     */
    public function isUpdated(): bool
    {
        return $this->isUpdated;
    }
}