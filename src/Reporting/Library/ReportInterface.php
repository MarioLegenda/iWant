<?php

namespace App\Reporting\Library;

interface ReportInterface
{
    /**
     * @return string
     */
    public function getReportType(): string;
    /**
     * @return ReportPresentationInterface
     */
    public function getReport(): ReportPresentationInterface;
}