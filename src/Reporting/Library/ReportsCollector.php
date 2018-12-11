<?php

namespace App\Reporting\Library;


use App\Doctrine\Entity\ExternalServiceReport;
use App\Doctrine\Repository\ExternalServiceReportRepository;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Reporting\Library\Factory\ReportConverter;

class ReportsCollector
{
    /**
     * @var ReportInterface[] $reports
     */
    private $reports = [];
    /**
     * @var ExternalServiceReport[] $reportsObjects
     */
    private $reportsObjects = [];
    /**
     * @var ExternalServiceReportRepository $externalServiceReportRepository
     */
    private $externalServiceReportRepository;
    /**
     * @var ReportConverter $reportConverter
     */
    private $reportConverter;
    /**
     * ReportsCollector constructor.
     * @param ExternalServiceReportRepository $externalServiceReportRepository
     * @param ReportConverter $reportConverter
     */
    public function __construct(
        ExternalServiceReportRepository $externalServiceReportRepository,
        ReportConverter $reportConverter
    ) {
        $this->externalServiceReportRepository = $externalServiceReportRepository;
        $this->reportConverter = $reportConverter;
    }
    /**
     * @param ReportInterface $report
     */
    public function addReport(
        ReportInterface $report
    ): void {
        $externalServiceType = (string) $report->getReportType();

        if (!array_key_exists($externalServiceType, $this->reports)) {
            /** @var ExternalServiceReport $externalServiceReport */
            $externalServiceReport = $this->externalServiceReportRepository->tryGetByType($externalServiceType);

            if ($externalServiceReport instanceof ExternalServiceReport) {
                $this->reports[$externalServiceReport->getExternalServiceType()] =
                    $this->reportConverter->createReport(
                        $externalServiceReport->getExternalServiceType(),
                        $externalServiceReport->getReportAsArray()
                    );

                return;
            }

            $this->reports[(string) $externalServiceType] = $report;
        }
    }
    /**
     * @return ExternalServiceReport[]|iterable|TypedArray
     */
    public function getCollectedReports(): ?iterable
    {
        $collectedReports = TypedArray::create('integer', ExternalServiceReport::class);

        /**
         * @var string $reportType
         * @var ReportInterface $report
         */
        foreach ($this->reports as $reportType => $report) {
            if (!array_key_exists($reportType, $this->reportsObjects)) {
                $collectedReports[] = new ExternalServiceReport(
                    $reportType,
                    $report->getReport()->getArrayReport()
                );
            } else if (array_key_exists($reportType, $this->reportsObjects)) {
                /** @var ExternalServiceReport $reportObject */
                $reportObject = $this->reportsObjects[$reportType];

                $reportObject->setReport($report->getReport()->getArrayReport());

                $collectedReports[] = $reportObject;
            }
        }

        if ($collectedReports->isEmpty()) {
            return null;
        }

        return $collectedReports;
    }
}