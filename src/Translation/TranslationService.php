<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 02/11/2018
 * Time: 12:28
 */

namespace App\Translation;


use App\Library\Util\Util;
use App\Yandex\Library\Request\RequestFactory;
use App\Yandex\Library\Response\DetectLanguageResponse;
use App\Yandex\Library\Response\TranslatedTextResponse;
use App\Yandex\Presentation\EntryPoint\YandexEntryPoint;
use App\Yandex\Presentation\Model\YandexRequestModel;

class TranslationService
{
    /**
     * @var YandexEntryPoint $yandexEntryPoint
     */
    private $yandexEntryPoint;
    /**
     * TranslationService constructor.
     * @param YandexEntryPoint $yandexEntryPoint
     */
    public function __construct(
        YandexEntryPoint $yandexEntryPoint
    ) {
        $this->yandexEntryPoint = $yandexEntryPoint;
    }
    /**
     * @param string $single
     * @param string $locale
     * @return string
     */
    public function translateSingle(string $single, string $locale): string
    {
        /** @var YandexRequestModel $detectLanguageModel */
        $detectLanguageModel = RequestFactory::createDetectLanguageRequestModel($single);

        /** @var DetectLanguageResponse $detectLanguageResponse */
        $detectLanguageResponse = $this->yandexEntryPoint->detectLanguage($detectLanguageModel);

        if ($detectLanguageResponse->getLang() === $locale) {
            return $single;
        }

        /** @var YandexRequestModel $translationRequestModel */
        $translationRequestModel = RequestFactory::createTranslateRequestModel($single, $locale);

        /** @var TranslatedTextResponse $translated */
        $translated = $this->yandexEntryPoint->translate($translationRequestModel);

        return $translated->getText();
    }
    /**
     * @param iterable $multiple
     * @param string $locale
     * @return array
     */
    public function translateMultiple(iterable $multiple, string $locale): array
    {
        $this->validate($multiple);

        $iterableGen = Util::createGenerator($multiple);

        $translated = [];
        foreach ($iterableGen as $entry) {
            $item = $entry['item'];
            $key = $entry['key'];

            $translated[$key] = $this->translateSingle($item, $locale);
        }

        return $translated;
    }
    /**
     * @param iterable $iterable
     */
    private function validate(iterable $iterable)
    {
        if (empty($iterable)) {
            $message = sprintf(
                'Multiple translations parameters cannot be empty'
            );

            throw new \RuntimeException($message);
        }

        $iterableGen = Util::createGenerator($iterable);

        foreach ($iterableGen as $entry) {
            $key = $entry['key'];

            if (!is_string($key)) {
                $message = sprintf(
                    'Every key in multiple translations has to be a string'
                );

                throw new \RuntimeException($message);
            }
        }
    }
}