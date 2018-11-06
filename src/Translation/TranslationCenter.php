<?php

namespace App\Translation;

use App\Cache\Implementation\ItemTranslationCacheImplementation;
use App\Component\Search\Ebay\Model\Request\Model\TranslationEntry;
use App\Component\Search\Ebay\Model\Request\Model\Translations;
use App\Doctrine\Entity\ItemTranslationCache;
use App\Library\Util\Environment;
use App\Yandex\Library\Request\RequestFactory;
use App\Yandex\Library\Response\DetectLanguageResponse;
use App\Yandex\Library\Response\TranslatedTextResponse;
use App\Yandex\Presentation\EntryPoint\YandexEntryPoint;
use App\Yandex\Presentation\Model\YandexRequestModel;
use Psr\Log\LoggerInterface;

class TranslationCenter
{
    /**
     * @var YandexEntryPoint $yandexEntryPoint
     */
    private $yandexEntryPoint;
    /**
     * @var ItemTranslationCacheImplementation $itemTranslationCacheImplementation
     */
    private $itemTranslationCacheImplementation;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * @var Environment $environment
     */
    private $environment;
    /**
     * TranslationService constructor.
     * @param YandexEntryPoint $yandexEntryPoint
     * @param ItemTranslationCacheImplementation $itemTranslationCacheImplementation
     * @param LoggerInterface $logger
     * @param Environment $environment
     */
    public function __construct(
        YandexEntryPoint $yandexEntryPoint,
        ItemTranslationCacheImplementation $itemTranslationCacheImplementation,
        LoggerInterface $logger,
        Environment $environment
    ) {
        $this->yandexEntryPoint = $yandexEntryPoint;
        $this->itemTranslationCacheImplementation = $itemTranslationCacheImplementation;
        $this->logger = $logger;
        $this->environment = $environment;
    }
    /**
     * @param string $entryId
     * @param string $uniqueName
     * @param string $itemId
     * @param string $value
     * @param string $locale
     * @return string
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function translateSingle(
        string $entryId,
        string $uniqueName,
        string $itemId,
        string $value,
        string $locale
    ): string {
        if ($this->itemTranslationCacheImplementation->isStored(
            $uniqueName,
            $itemId
        )) {
            /** @var ItemTranslationCache $itemTranslationCache */
            $itemTranslationCache = $this->itemTranslationCacheImplementation->getStored(
                $uniqueName,
                $itemId
            );

            /** @var Translations $translations */
            $translations = $this->createTranslations(json_decode($itemTranslationCache->getTranslations(), true));

            if ($translations->hasEntryByLocale($entryId, $locale)) {
                /** @var TranslationEntry $translationEntry */
                $translationEntry = $translations->getEntryByLocale($entryId, $locale);

                return $translationEntry->getTranslation();
            }

            if ((string) $this->environment === 'dev' OR (string) $this->environment === 'test') {
                $translated = $this->translate($locale, $value);
            } else if ((string) $this->environment === 'prod') {
                try {
                    $translated = $this->translate($locale, $value);
                } catch (\Exception $e) {
                    $message = sprintf(
                        'Translation for locale %s and value %s could not be translated by Yandex api',
                        $locale,
                        $value
                    );

                    $this->logger->warning($message);

                    $translated = $value;
                }
            }

            $translations->putTranslation(
                $entryId,
                $locale,
                $value,
                $translated
            );

            $this->itemTranslationCacheImplementation->update(
                $uniqueName,
                $itemId,
                $translations->toArray()
            );

            return $translated;
        }

        $translations = $this->createTranslations();

        if ((string) $this->environment === 'dev' OR (string) $this->environment === 'test') {
            $translated = $this->translate($locale, $value);
        } else if ((string) $this->environment === 'prod') {
            try {
                $translated = $this->translate($locale, $value);
            } catch (\Exception $e) {
                $message = sprintf(
                    'Translation for locale %s and value %s could not be translated by Yandex api',
                    $locale,
                    $value
                );

                $this->logger->warning($message);

                $translated = $value;
            }
        }

        $translations->putTranslation(
            $entryId,
            $locale,
            $value,
            $translated
        );

        $this->itemTranslationCacheImplementation->store(
            $uniqueName,
            $itemId,
            $translations->toArray()
        );

        return $translated;
    }
    /**
     * @param string $locale
     * @param string $value
     * @return string
     */
    private function translate(
        string $locale,
        string $value
    ): string {
        /** @var YandexRequestModel $detectLanguageModel */
        $detectLanguageModel = RequestFactory::createDetectLanguageRequestModel($value);

        /** @var DetectLanguageResponse $detectLanguageResponse */
        $detectLanguageResponse = $this->yandexEntryPoint->detectLanguage($detectLanguageModel);

        if ($detectLanguageResponse->getLang() === $locale) {
            return $value;
        }

        /** @var YandexRequestModel $translationRequestModel */
        $translationRequestModel = RequestFactory::createTranslateRequestModel($value, $locale);

        /** @var TranslatedTextResponse $translated */
        $translated = $this->yandexEntryPoint->translate($translationRequestModel);

        return $translated->getText();
    }
    /**
     * @param array $translations
     * @return Translations
     */
    private function createTranslations(array $translations = []): Translations
    {
        return new Translations($translations);
    }
}