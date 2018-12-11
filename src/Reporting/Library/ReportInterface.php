<?php

namespace App\Reporting\Library;

use App\Library\Infrastructure\Type\TypeInterface;
use App\Reporting\Library\Type\YandexReportType;

interface ReportInterface
{
    /**
     * @return TypeInterface|YandexReportType
     */
    public function getReportType(): TypeInterface;
    /**
     * @return ReportPresentationInterface
     */
    public function getReport(): ReportPresentationInterface;
}