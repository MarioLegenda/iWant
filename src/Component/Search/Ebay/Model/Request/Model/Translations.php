<?php

namespace App\Component\Search\Ebay\Model\Request\Model;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class Translations implements ArrayNotationInterface
{
    /**
     * @var array $translations
     */
    private $translations = [];
    /**
     * Translations constructor.
     * @param array $translations
     */
    public function __construct(array $translations = [])
    {
        if (!empty($translations)) {
            $transGen = Util::createGenerator($translations);

            foreach ($transGen as $entry) {
                $item = $entry['item'];
                $entryId = $entry['key'];

                $itemsGen = Util::createGenerator($item);

                foreach ($itemsGen as $item) {
                    $locale = $item['item']['locale'];
                    $original = $item['item']['original'];
                    $translation = $item['item']['translated'];

                    $this->translations[$entryId][] = new TranslationEntry(
                        $locale,
                        $original,
                        $translation
                    );
                }
            }
        }
    }
    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->translations);
    }
    /**
     * @param string $entryId
     * @param string $locale
     * @param string $original
     * @param string $translation
     */
    public function putTranslation(
        string $entryId,
        string $locale,
        string $original,
        string $translation
    ): void {
        $translationEntry = new TranslationEntry(
            $locale,
            $original,
            $translation
        );

        $this->translations[$entryId][] = $translationEntry;
    }
    /**
     * @param string $entryId
     * @param string $locale
     * @return bool
     */
    public function hasEntryByLocale(string $entryId, string $locale): bool
    {
        if (!array_key_exists($entryId, $this->translations)) {
            return false;
        }

        $translationEntries = $this->translations[$entryId];

        $entriesGen = Util::createGenerator($translationEntries);

        foreach ($entriesGen as $entry) {
            /** @var TranslationEntry $item */
            $item = $entry['item'];

            if ($item->isLocale($locale)) {
                return true;
            }
        }

        return false;
    }
    /**
     * @param string $entryId
     * @param string $locale
     * @return TranslationEntry
     */
    public function getEntryByLocale(
        string $entryId,
        string $locale
    ): TranslationEntry {
        $translationEntries = $this->translations[$entryId];

        $entriesGen = Util::createGenerator($translationEntries);

        foreach ($entriesGen as $entry) {
            /** @var TranslationEntry $item */
            $item = $entry['item'];

            if ($item->isLocale($locale)) {
                return $item;
            }
        }
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        $transGen = Util::createGenerator($this->translations);

        $translations = [];
        foreach ($transGen as $entry) {
            /** @var TranslationEntry[] $item */
            $item  = $entry['item'];
            $entryId = $entry['key'];

            $entriesGen = Util::createGenerator($item);

            foreach ($entriesGen as $transEntry) {
                /** @var TranslationEntry $transItem */
                $transItem = $transEntry['item'];

                $translations[$entryId][] = $transItem->toArray();
            }
        }

        return $translations;
    }
}