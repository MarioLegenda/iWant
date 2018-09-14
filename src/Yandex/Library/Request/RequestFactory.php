<?php

namespace App\Yandex\Library\Request;


use App\Yandex\Presentation\Model\YandexRequestModel;
use App\Yandex\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;

class RequestFactory
{
    /**
     * @param string $text
     * @param string $lang
     * @return YandexRequestModel
     */
    public static function createTranslateRequestModel(string $text, string $lang): YandexRequestModel
    {
        $textQuery = new Query(
            'text',
            urlencode($text)
        );

        $format = new Query(
            'format',
            'plain'
        );

        $langQuery = new Query(
            'lang',
            $lang
        );

        $callType = CallType::fromValue('translate');

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $textQuery;
        $queries[] = $format;
        $queries[] = $langQuery;

        return new YandexRequestModel(
            $callType,
            $queries
        );
    }
}