<?php

namespace App\Web\Library\Grouping\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class HighestPriceGroupingType extends BaseType
{
    const HIGHEST_PRICE = 'highest_price';
    /**
     * @var array $types
     */
    protected static $types = [
        HighestPriceGroupingType::HIGHEST_PRICE,
    ];
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'stub_value'): TypeInterface
    {
        return parent::fromValue(HighestPriceGroupingType::HIGHEST_PRICE);
    }
}