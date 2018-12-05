<?php

namespace App\Translation;

use App\Cache\Implementation\ItemTranslationCacheImplementation;
use App\Component\Search\Ebay\Model\Request\Model\TranslationEntry;
use App\Component\Search\Ebay\Model\Request\Model\Translations;
use App\Doctrine\Entity\ItemTranslationCache;
use App\Library\Util\Environment;
use App\Library\Util\Util;
use App\Translation\Model\Language;
use App\Translation\Model\TranslatedEntryInterface;
use App\Translation\Model\Translation;
use App\Yandex\Presentation\EntryPoint\YandexEntryPoint;
use Psr\Log\LoggerInterface;

class YandexCacheableTranslationCenter implements TranslationCenterInterface
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
        YandexTranslationCenter $yandexEntryPoint,
        ItemTranslationCacheImplementation $itemTranslationCacheImplementation,
        LoggerInterface $logger,
        Environment $environment
    ) {
        $this->yandexEntryPoint = $yandexEntryPoint;
        $this->itemTranslationCacheImplementation = $itemTranslationCacheImplementation;
        $this->logger = $logger;
        $this->environment = $environment;
    }

    public function translateFromTo(Language $from, Language $to, string $text): Translation
    {
        throw new \RuntimeException('Not yet implemented');
    }
    /**
     * @param string $value
     * @param string $locale
     * @param string|null $entryId
     * @param string|null $identifier
     * @return TranslatedEntryInterface
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function translate(
        string $value,
        string $locale,
        string $entryId = null,
        string $identifier = null
    ): TranslatedEntryInterface {
        if (!is_string($locale) or !is_string($identifier)) {
            $message = sprintf(
                '$locale and $identifier have to be for %s::%s',
                get_class($this),
                __FUNCTION__
            );

            throw new \RuntimeException($message);
        }

        if ($this->itemTranslationCacheImplementation->isStored(
            $identifier
        )) {
            if (is_null($value)) {
                return new Translation('');
            }

            /** @var ItemTranslationCache $itemTranslationCache */
            $itemTranslationCache = $this->itemTranslationCacheImplementation->getStored(
                $identifier
            );

            /** @var Translations $translations */
            $translations = $this->createTranslations(json_decode($itemTranslationCache->getTranslations(), true));

            if ($translations->hasEntryByLocale($entryId, $locale)) {
                /** @var TranslationEntry $translationEntry */
                $translationEntry = $translations->getEntryByLocale($entryId, $locale);

                return new Translation($translationEntry->getTranslation());
            }

            if ((string) $this->environment === 'dev' OR (string) $this->environment === 'test') {
                $translated = $this->yandexEntryPoint->translate($locale, $value);
            } else if ((string) $this->environment === 'prod') {
                try {
                    $translated = $this->yandexEntryPoint->translate($locale, $value);
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
                $identifier,
                $translations->toArray()
            );

            return new Translation($translated);
        }

        $translations = $this->createTranslations();

        if ((string) $this->environment === 'dev' OR (string) $this->environment === 'test') {
            $translated = $this->yandexEntryPoint->translate($locale, $value);
        } else if ((string) $this->environment === 'prod') {
            try {
                $translated = $this->yandexEntryPoint->translate($locale, $value);
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
            $identifier,
            $translations->toArray()
        );

        return new Translation($translated);
    }
    /**
     * @param array $item
     * @param array $keys
     * @param string|null $locale
     * @param string|null $identifier
     * @return array
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function translateArray(
        array $item,
        array $keys,
        string $locale = null,
        string $identifier = null
    ): array {
        if (!is_string($locale) or !is_string($identifier)) {
            $message = sprintf(
                '$locale and $identifier have to be for %s::%s',
                get_class($this),
                __FUNCTION__
            );

            throw new \RuntimeException($message);
        }

        $keysToTranslateGen = Util::createGenerator($keys);

        foreach ($keysToTranslateGen as $entry) {
            $translationConfig = $this->createTranslationConfig(
                $entry['key'],
                $entry['item']
            );

            if ($translationConfig->isEventTranslation()) {
                $value = $translationConfig->getPreTranslationEvent()->__invoke(
                    $translationConfig->getKey(),
                    $item
                );

                if (empty($value)) {
                    $item[$translationConfig->getKey()] = '';

                    continue;
                }

                $translated = $this->translate(
                    $value,
                    $locale,
                    $translationConfig->getKey(),
                    $identifier
                );

                $item[$translationConfig->getKey()] = $translationConfig->getPostTranslationEvent()->__invoke(
                    $translationConfig->getKey(),
                    $translated->getEntry()
                );
            }

            if (!$translationConfig->isEventTranslation()) {
                $value = $item[$translationConfig->getKey()];

                if (empty($value)) {
                    $item[$translationConfig->getKey()] = '';

                    continue;
                }

                $translation = $this->translate(
                    $value,
                    $locale,
                    $translationConfig->getKey(),
                    $identifier
                );

                $item[$translationConfig->getKey()] = $translation->getEntry();
            }

            // post translate event
        }

        return $item;
    }

    public function detectLanguage(string $text): TranslatedEntryInterface
    {
        // TODO: Implement detectLanguage() method.
    }

    /**
     * @param array $translations
     * @return Translations
     */
    private function createTranslations(array $translations = []): Translations
    {
        return new Translations($translations);
    }
    /**
     * @param $key
     * @param $item
     * @return object
     */
    private function createTranslationConfig($key, $item): object
    {
        return new class($key, $item)
        {
            /**
             * @var string $key
             */
            private $key;
            /**
             * @var string $item
             */
            private $item;
            /**
             * @var bool $isEventTranslation
             */
            private $isEventTranslation = false;
            /**
             * @var \Closure $preTranslateEvent
             */
            private $preTranslateEvent;
            /**
             * @var \Closure $postTranslateEvent
             */
            private $postTranslateEvent;
            /**
             *  constructor.
             * @param $key
             * @param $item
             */
            public function __construct($key, $item)
            {
                if (is_string($key) and is_array($item)) {
                    if (array_key_exists('pre_translate', $item) and
                        array_key_exists('post_translate', $item)) {

                        if (!$item['pre_translate'] instanceof \Closure) {
                            throw new \RuntimeException('pre_translate event should be an anonymous function');
                        }

                        if (!$item['post_translate'] instanceof \Closure) {
                            throw new \RuntimeException('post_translate event should be an anonymous function');
                        }

                        $this->preTranslateEvent = $item['pre_translate'];
                        $this->postTranslateEvent = $item['post_translate'];

                        $this->isEventTranslation = true;
                    }
                }

                $this->key = $key;
                $this->item = $item;
            }
            /**
             * @return string
             */
            public function getKey(): string
            {
                if ($this->isEventTranslation()) {
                    return $this->key;
                }

                return $this->item;
            }
            /**
             * @return bool
             */
            public function isEventTranslation(): bool
            {
                return $this->isEventTranslation;
            }
            /**
             * @return \Closure
             */
            public function getPreTranslationEvent(): \Closure
            {
                return $this->preTranslateEvent;
            }
            /**
             * @return \Closure
             */
            public function getPostTranslationEvent(): \Closure
            {
                return $this->postTranslateEvent;
            }
        };
    }

}