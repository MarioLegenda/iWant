<?php

namespace App\Tests\Yandex\DataProvider;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Yandex\Library\Request\CallType;
use App\Yandex\Presentation\Model\Query;
use App\Yandex\Presentation\Model\YandexRequestModel;
use App\Yandex\Presentation\Model\YandexRequestModelInterface;

class DataProvider
{
    /**
     * @param string $language
     * @return YandexRequestModelInterface
     */
    public function getSupportedLanguagesModel(string $language): YandexRequestModelInterface
    {
        $uiQuery = new Query(
            'ui',
            $language
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $uiQuery;

        return new YandexRequestModel(
            CallType::fromValue('getLangs'),
            $queries
        );
    }
    /**
     * @param string $text
     * @return YandexRequestModelInterface
     */
    public function getDetectLanguage(string $text): YandexRequestModelInterface
    {
        $textQuery = new Query(
            'text',
            urlencode($text)
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $textQuery;

        return new YandexRequestModel(
            CallType::fromValue('detect'),
            $queries
        );
    }
}