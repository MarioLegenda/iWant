<?php

namespace App\Yandex\Presentation\Model;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Yandex\Library\Request\CallType;

class YandexRequestModel implements YandexRequestModelInterface
{
    /**
     * @var TypedArray|Query[] $queries
     */
    private $queries;
    /**
     * @var CallType $callType
     */
    private $callType;
    /**
     * YandexRequestModel constructor.
     * @param CallType|TypeInterface $callType
     * @param TypedArray $queries
     */
    public function __construct(
        CallType $callType,
        TypedArray $queries
    ) {
        $this->callType = $callType;
        $this->queries = $queries;
    }
    /**
     * @return CallType
     */
    public function getCallType(): CallType
    {
        return $this->callType;
    }
    /**
     * @return TypedArray
     */
    public function getQueries(): TypedArray
    {
        return $this->queries;
    }
}