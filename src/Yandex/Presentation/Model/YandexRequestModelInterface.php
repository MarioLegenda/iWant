<?php

namespace App\Yandex\Presentation\Model;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Yandex\Library\Request\CallType;

interface YandexRequestModelInterface
{
    /**
     * @return TypedArray
     */
    public function getQueries(): TypedArray;
    /**
     * @return CallType
     */
    public function getCallType(): CallType;
}