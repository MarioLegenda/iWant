<?php

namespace App\Reporting\Presentation\Model;

use App\Library\Infrastructure\Type\TypeInterface;
use App\Reporting\Library\ReportInterface;
use App\Reporting\Library\ReportPresentationInterface;
use App\Reporting\Library\Type\YandexReportType;

class YandexTranslationServiceReport implements ReportInterface
{
    /**
     * @var TypeInterface|YandexReportType $reportType
     */
    private $reportType;
    /**
     * @var ReportPresentationInterface $reportPresentation
     */
    private $reportPresentation;
    /**
     * YandexTranslationService constructor.
     * @param ReportPresentationInterface $reportPresentation
     */
    public function __construct(
        ReportPresentationInterface $reportPresentation
    ) {
        $this->reportType = YandexReportType::fromValue();
        $this->reportPresentation = $reportPresentation;
    }
    /**
     * @return ReportPresentationInterface
     */
    public function getReport(): ReportPresentationInterface
    {
        return $this->reportPresentation;
    }
    /**
     * @return TypeInterface|YandexReportType
     */
    public function getReportType(): TypeInterface
    {
        return $this->reportType;
    }
}