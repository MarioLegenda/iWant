<?php

namespace App\Translation;

use App\Library\Util\Util;
use App\Translation\Model\Language;
use App\Translation\Model\TranslatedEntryInterface;
use App\Translation\Model\Translation;
use App\Yandex\Library\Request\RequestFactory;
use App\Yandex\Library\Model\DetectLanguageResponse;
use App\Yandex\Library\Model\TranslatedTextResponse;
use App\Yandex\Presentation\EntryPoint\YandexEntryPoint;
use App\Yandex\Presentation\Model\YandexRequestModel;

class YandexTranslationCenter implements TranslationCenterInterface
{
    /**
     * @var YandexEntryPoint $yandexEntryPoint
     */
    private $yandexEntryPoint;
    /**
     * YandexTranslationCenter constructor.
     * @param YandexEntryPoint $yandexEntryPoint
     */
    public function __construct(
        YandexEntryPoint $yandexEntryPoint
    ) {
        $this->yandexEntryPoint = $yandexEntryPoint;
    }
    /**
     * @param Language $from
     * @param Language $to
     * @param string $text
     * @return Translation
     */
    public function translateFromTo(Language $from, Language $to, string $text): Translation
    {
        $fromTo = sprintf('%s-%s', (string) $from, (string) $to);

        /** @var YandexRequestModel $translationRequestModel */
        $translationRequestModel = RequestFactory::createTranslateRequestModel($text, $fromTo);

        /** @var TranslatedTextResponse $translated */
        $translated = $this->yandexEntryPoint->translate($translationRequestModel);

        return new Translation($translated->getText());
    }
    /**
     * @param string $locale
     * @param string $value
     * @return TranslatedEntryInterface
     */
    public function translate(
        string $locale,
        string $value
    ): TranslatedEntryInterface {
        if ($this->detectLanguage($value)->getEntry() === $locale) {
            return new Language($value);
        }

        /** @var YandexRequestModel $translationRequestModel */
        $translationRequestModel = RequestFactory::createTranslateRequestModel($value, $locale);

        /** @var TranslatedTextResponse $translated */
        $translated = $this->yandexEntryPoint->translate($translationRequestModel);

        return new Translation($translated->getText());
    }
    /**
     * @param array $item
     * @param array $keysToTranslate
     * @return array
     */
    public function translateArray(
        array $item,
        array $keysToTranslate
    ): array {

        $keysToTranslateGen = Util::createGenerator($keysToTranslate);

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
                    $this->detectLanguage($value)->getEntry(),
                    $value
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
                    $this->detectLanguage($value)->getEntry(),
                    $value
                );

                $item[$translationConfig->getKey()] = $translation->getEntry();
            }

            // post translate event
        }

        return $item;
    }
    /**
     * @param string $text
     * @return Language
     */
    public function detectLanguage(string $text): TranslatedEntryInterface
    {
        /** @var YandexRequestModel $detectLanguageModel */
        $detectLanguageModel = RequestFactory::createDetectLanguageRequestModel($text);

        /** @var DetectLanguageResponse $detectLanguageResponse */
        $detectLanguageResponse = $this->yandexEntryPoint->detectLanguage($detectLanguageModel);

        return new Language($detectLanguageResponse->getLang());
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