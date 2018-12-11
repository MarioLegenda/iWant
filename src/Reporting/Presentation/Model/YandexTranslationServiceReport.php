<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 2018-12-10
 * Time: 20:01
 */

namespace App\Reporting\Presentation\Model;

use App\Reporting\Library\ReportInterface;
use App\Reporting\Library\ReportPresentationInterface;

class YandexTranslationServiceReport implements ReportInterface
{
    /**
     * @var string $reportType
     */
    private $reportType = 'yandex_translation_report';
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
     * @return string
     */
    public function getReportType(): string
    {
        return $this->reportType;
    }
}