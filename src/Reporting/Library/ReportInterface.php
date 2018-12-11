<?php

namespace App\Reporting\Library;

interface ReportInterface
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