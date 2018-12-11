<?php

namespace App\Reporting\Library;


use App\Doctrine\Entity\ExternalServiceReport;
use App\Doctrine\Repository\ExternalServiceReportRepository;

class ReportsCollector
{
    /**
     * @var ReportInterface[] $reports
     */
    private $reports = [];
    /**
     * @var ExternalServiceReportRepository $externalServiceReportRepository
     */
    private $externalServiceReportRepository;
    /**
     * ReportsCollector constructor.
     * @param ExternalServiceReportRepository $externalServiceReportRepository
     */
    public function __construct(ExternalServiceReportRepository $externalServiceReportRepository)
    {
        $this->externalServiceReportRepository = $externalServiceReportRepository;
    }
    /**
     * @param ReportInterface $report
     */
    public function addReport(
        ReportInterface $report
    ): void {
        $externalServiceType = $report->getReportType();

        if (!array_key_exists($externalServiceType, $this->reports)) {
            $externalServiceReport = $this->externalServiceReportRepository->getByType($externalServiceType);

            if (!$externalServiceReport instanceof ExternalServiceReport) {

            }
        }


    }
}