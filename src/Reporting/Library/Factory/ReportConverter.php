<?php

namespace App\Reporting\Library\Factory;

use App\Reporting\Library\ReportInterface;
use App\Reporting\Library\Type\YandexReportType;
use App\Reporting\Presentation\Model\YandexTranslationServiceReport;
use App\Reporting\Presentation\Model\YandexTranslationServiceReportPresentation;

class ReportConverter
{
    /**
     * @param string $type
     * @param array $data
     * @return ReportInterface
     */
    public function createReport(
        string $type,
        array $data
    ): ReportInterface {
        if ((string) YandexReportType::fromValue() === $type) {
            return new YandexTranslationServiceReport(
                new YandexTranslationServiceReportPresentation($data)
            );
        }
    }
}