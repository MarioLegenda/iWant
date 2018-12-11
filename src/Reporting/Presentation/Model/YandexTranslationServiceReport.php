<?php

namespace App\Reporting\Presentation\Model;

use App\Reporting\Library\ReportInterface;

class YandexTranslationServiceReport implements ReportInterface
{
    /**
     * @var array $report
     */
    private $report = [
        'hitCount' => 0,
        'characterCount' => 0,
    ];
    /**
     * @void
     */
    public function incrementHitCount(): void
    {
        $this->report['hitCount']++;
    }
    /**
     * @return int
     */
    public function getHitCount(): int
    {
        return $this->report['hitCount'];
    }
    /**
     * @return int
     */
    public function getCharacterCount(): int
    {
        return $this->report['characterCount'];
    }
    /**
     * @param int $count
     */
    public function incrementCharacterCount(int $count): void
    {
        $currentCharCount = $this->report['characterCount'];

        $currentCharCount += $count;

        $this->report['characterCount'] = $currentCharCount;
    }
    /**
     * @return array
     */
    public function getArrayReport(): array
    {
        return $this->report;
    }
    /**
     * @return string
     */
    public function getJsonReport(): string
    {
        return jsonEncodeWithFix($this->report);
    }
}