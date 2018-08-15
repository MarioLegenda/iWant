<?php

namespace App\Web\Model\Response;

use App\Library\Infrastructure\Type\TypeInterface;
use App\Web\Model\Response\Type\DeferrableType;

class DeferrableHttpDataObject implements DeferrableHttpDataObjectInterface
{
    /**
     * @var iterable $deferrableData
     */
    private $deferrableData;
    /**
     * DeferrableHttpDataObject constructor.
     * @param iterable $deferrableData
     */
    public function __construct(iterable $deferrableData)
    {
        $this->deferrableData = $deferrableData;
    }
    /**
     * @inheritdoc
     */
    public function getDeferrableType(): TypeInterface
    {
        return DeferrableType::fromValue('http_deferrable');
    }
    /**
     * @return iterable
     */
    public function getDeferrableData(): iterable
    {
        return $this->deferrableData;
    }
}