<?php

namespace App\Translation;

use App\Library\Util\Util;
use App\Translation\Model\Language;
use App\Translation\Model\TranslatedEntryInterface;
use App\Translation\Model\Translation;
use Google\Cloud\Translate\TranslateClient;

class GoogleTranslationCenter implements TranslationCenterInterface
{
    /**
     * @var string $googleTranslationApiId
     */
    private $googleTranslationApiId;
    /**
     * @var TranslateClient $client
     */
    private $client;
    /**
     * GoogleTranslationCenter constructor.
     * @param string $googleTranslationApiId
     */
    public function __construct(
        string $googleTranslationApiId
    ) {
        $this->googleTranslationApiId = $googleTranslationApiId;

        $this->client = new TranslateClient([
            'projectId' => $this->googleTranslationApiId
        ]);
    }
    /**
     * @param string $text
     * @return TranslatedEntryInterface
     */
    public function detectLanguage(string $text): TranslatedEntryInterface
    {
        $result = $this->client->detectLanguage($text);

        return new Language($result['languageCode']);
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
     * @param string $value
     * @param string $locale
     * @return TranslatedEntryInterface
     */
    public function translate(string $value, string $locale): TranslatedEntryInterface
    {
        $result = $this->client->translate($value, [
            'target' => $locale,
        ]);

        if (is_array($result)) {
            return new Translation($result['text']);
        }

        return new Translation($value);
    }
    /**
     * @param Language $from
     * @param Language $to
     * @param string $text
     * @return Translation
     */
    public function translateFromTo(Language $from, Language $to, string $text): Translation
    {
        $result = $this->client->translate($text, [
            'target' => (string) $to,
            'source' => (string) $from,
        ]);

        if (is_array($result)) {
            return new Translation($result['text']);
        }

        return new Translation($text);
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