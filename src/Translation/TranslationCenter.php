<?php

namespace App\Translation;

use App\Cache\Implementation\ItemTranslationCacheImplementation;
use App\Component\Search\Ebay\Model\Request\Model\TranslationEntry;
use App\Component\Search\Ebay\Model\Request\Model\Translations;
use App\Doctrine\Entity\ItemTranslationCache;
use App\Library\Util\Environment;
use App\Library\Util\Util;
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
        string $itemId,
        string $value,
        string $locale
    ): string {
        if ($this->itemTranslationCacheImplementation->isStored(
            $itemId
        )) {
            /** @var ItemTranslationCache $itemTranslationCache */
            $itemTranslationCache = $this->itemTranslationCacheImplementation->getStored(
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
                        'Translation for locale %s and value %s could not be translated by Yandex API',
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
            $itemId,
            $translations->toArray()
        );

        return $translated;
    }

    public function translateMultiple(
        array $item,
        array $keys,
        Translations $existingTranslations,
        string $locale,
        bool $translateIfNotExists = false,
        string $itemId = null
    ): array {
        if ($translateIfNotExists) {
            if (!is_string($itemId)) {
                $message = sprintf(
                    'Invalid parameters passed to %s::translateMultiple(). If you choose to translate an entry if the entry does not exist, you have to provide the entries itemId. itemId has not been provided',
                    get_class($this)
                );

                throw new \RuntimeException($message);
            }
        }

        $keysToTranslateGen = Util::createGenerator($keys);

        foreach ($keysToTranslateGen as $entry) {
            $key = $entry['item'];

            if ($item[$key] === null) {
                $item[$key] = '';

                continue;
            }

            if ($existingTranslations->hasEntryByLocale($key, $locale)) {
                $item[$key] = $existingTranslations->getEntryByLocale($key, $locale)->getTranslation();
            } else if ($translateIfNotExists) {
                $item[$key] = $this->translateSingle(
                    $key,
                    $itemId,
                    $item[$key],
                    $locale
                );
            }
        }

        return $item;
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