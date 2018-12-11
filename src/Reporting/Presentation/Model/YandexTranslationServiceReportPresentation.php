<?php

namespace App\Reporting\Presentation\Model;

use App\Reporting\Library\ReportPresentationInterface;

class YandexTranslationServiceReportPresentation implements ReportPresentationInterface
{
    /**
     * @var array $report
     */
    private $report = [
        'hitCount' => 0,
        'characterCount' => 0,
    ];
    /**
     * @var array $mandatoryFields
     */
    private $mandatoryFields = ['hitCount', 'characterCount'];
    /**
     * YandexTranslationServiceReportPresentation constructor.
     * @param array $report
     */
    public function __construct(array $report)
    {
        foreach ($this->mandatoryFields as $field) {
            if (!array_key_exists($field, $report)) {
                $message = sprintf(
                    '%s can be changed only trough special methods',
                    implode(',', $this->mandatoryFields)
                );

                throw new \RuntimeException($message);
            }
        }

        $this->report = $report;
    }
    /**
     * @param string $type
     * @param array| $entry
     *
     * Only top level entries will be considered for change, there
     * will be no recursive check if some deeply nested entry has changed
     * because fuck that
     */
    public function addReportEntryByType(string $type, array $entry): void
    {
        if (in_array($type, $this->mandatoryFields) === true) {
            $message = sprintf(
                '%s can be changed only trough special methods, %s given',
                implode(',', $this->mandatoryFields),
                $type
            );

            throw new \RuntimeException($message);
        }

        $this->report[$type] = $entry;
    }
    /**
     * @param array $entry
     * @param \Closure $invocable
     */
    public function addInvocableReportEntry(array $entry, \Closure $invocable): void
    {
        $changedEntry = $invocable->__invoke($entry);

        foreach ($this->mandatoryFields as $field) {
            if (!array_key_exists($field, $changedEntry)) {
                $message = sprintf(
                    '%s can be changed only trough special methods',
                    implode(',', $this->mandatoryFields)
                );

                throw new \RuntimeException($message);
            }
        }
    }
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