<?php

namespace App\Reporting\Library;

interface ReportPresentationInterface
{
    /**
     * @return string
     */
    public function getJsonReport(): string;
    /**
     * @return array
     */
    public function getArrayReport(): array;
}