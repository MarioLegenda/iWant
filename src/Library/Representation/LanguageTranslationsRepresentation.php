<?php

namespace App\Library\Representation;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class LanguageTranslationsRepresentation implements ArrayNotationInterface
{
    /**
     * @var array $ebaySiteLanguageLocaleMapping
     */
    private $ebaySiteLanguageLocaleMapping;
    /**
     * LanguageTranslationsRepresentation constructor.
     * @param $ebaySiteLanguageLocaleMapping
     */
    public function __construct(
        $ebaySiteLanguageLocaleMapping
    ) {
        $this->ebaySiteLanguageLocaleMapping = $ebaySiteLanguageLocaleMapping;

        $this->validate();
    }
    /**
     * @param string $globalId
     * @return bool
     */
    public function areLocalesIdentical(string $globalId): bool
    {
        $this->checkGlobalIdExistence($globalId, __FUNCTION__);

        $localeMetadata = $this->getLocaleMetadata($globalId);

        return $localeMetadata->equals();
    }
    /**
     * @param string $globalId
     * @return array
     */
    public function getLocalesByGlobalId(string $globalId): array
    {
        $this->checkGlobalIdExistence($globalId, __FUNCTION__);

        return $this->getLocaleMetadata($globalId)->toArray();
    }
    /**
     * @param string $globalId
     * @return string
     */
    public function getLocaleByGlobalId(string $globalId): string
    {
        $this->checkGlobalIdExistence($globalId, __FUNCTION__);

        return $this->getLocaleMetadata($globalId)->getLocale();
    }
    /**
     * @param string $globalId
     * @return string
     */
    public function getMainLocaleByGlobalId(string $globalId): string
    {
        $this->checkGlobalIdExistence($globalId, __FUNCTION__);

        return $this->getLocaleMetadata($globalId)->getMainLocale();
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return $this->ebaySiteLanguageLocaleMapping;
    }
    /**
     * @param string $globalId
     * @param string $method
     */
    private function checkGlobalIdExistence(string $globalId, string $method)
    {
        if (!array_key_exists($globalId, $this->ebaySiteLanguageLocaleMapping)) {
            $message = sprintf(
                'Global id %s does not exist in method %s',
                get_class($this),
                $method
            );

            throw new \RuntimeException($message);
        }
    }

    private function validate(): void
    {
        $metadataGen = Util::createGenerator($this->ebaySiteLanguageLocaleMapping);

        foreach ($metadataGen as $entry) {
            $globalId = $entry['key'];

            $localeMetadata = $this->getLocaleMetadata($globalId)->toArray();

            if (!array_key_exists('locale', $localeMetadata) and !array_key_exists('mainLocale', $localeMetadata)) {
                $message = sprintf(
                    'Either entries \'locale\' or \'mainLocale\' do not exist in the site locale mapping configuration for global id %s',
                    $globalId
                );

                throw new \RuntimeException($message);
            }
        }
    }
    /**
     * @param string $globalId
     * @return object
     */
    private function getLocaleMetadata(string $globalId): object
    {
        $localeMetadata = $this->ebaySiteLanguageLocaleMapping[$globalId];

        return new class($localeMetadata) implements ArrayNotationInterface{
            /**
             * @var array $localeMetadata
             */
            private $localeMetadata;
            /**
             *  constructor.
             * @param array $localeMetadata
             */
            public function __construct(array $localeMetadata)
            {
                $this->localeMetadata = $localeMetadata;
            }
            /**
             * @return string
             */
            public function getLocale(): string
            {
                return $this->localeMetadata['locale'];
            }
            /**
             * @return string
             */
            public function getMainLocale(): string
            {
                return $this->localeMetadata['mainLocale'];
            }
            /**
             * @return bool
             */
            public function equals(): bool
            {
                return $this->getLocale() === $this->getMainLocale();
            }
            /**
             * @return iterable
             */
            public function toArray(): iterable
            {
                return $this->localeMetadata;
            }
        };
    }
}