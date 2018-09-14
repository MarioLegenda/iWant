<?php

namespace App\Tests\Yandex;

use App\Tests\Library\BasicSetup;
use App\Tests\Yandex\DataProvider\DataProvider;
use App\Yandex\Library\Response\DetectLanguageResponse;
use App\Yandex\Library\Response\Language;
use App\Yandex\Library\Response\SupportedLanguagesResponse;
use App\Yandex\Presentation\EntryPoint\YandexEntryPoint;
use App\Yandex\Presentation\Model\YandexRequestModelInterface;

class YandexTest extends BasicSetup
{
    public function test_get_supported_languages()
    {
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component.yandex');
        /** @var YandexEntryPoint $entryPoint */
        $entryPoint = $this->locator->get(YandexEntryPoint::class);

        /** @var YandexRequestModelInterface $model */
        $model = $dataProvider->getSupportedLanguagesModel('de');

        /** @var SupportedLanguagesResponse $response */
        $response = $entryPoint->getSupportedLanguages($model);

        $languages = $response->getAllLanguages();

        foreach ($languages as $code => $language) {
            static::assertTrue($response->isSupported($code));

            static::assertInstanceOf(Language::class, $response->getLanguage($code));
        }
    }

    public function test_detect_language()
    {
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.component.yandex');
        /** @var YandexEntryPoint $entryPoint */
        $entryPoint = $this->locator->get(YandexEntryPoint::class);

        $model = $dataProvider->getDetectLanguage('This is english');

        /** @var DetectLanguageResponse $response */
        $response = $entryPoint->detectLanguage($model);

        static::assertEquals(200, $response->getStatusCode());
        static::assertEquals('en', $response->getLang());

        $model = $dataProvider->getDetectLanguage('Eso es espanyol');

        /** @var DetectLanguageResponse $response */
        $response = $entryPoint->detectLanguage($model);

        static::assertEquals(200, $response->getStatusCode());
        static::assertEquals('es', $response->getLang());
    }
}