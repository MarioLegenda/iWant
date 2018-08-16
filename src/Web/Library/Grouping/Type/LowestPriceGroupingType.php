<?php

namespace App\Web\Library\Grouping\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class LowestPriceGroupingType extends BaseType
{
    const LOWEST_PRICE = 'lowest_price';
    /**
     * @var array $types
     */
    protected static $types = [
        LowestPriceGroupingType::LOWEST_PRICE,
    ];
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'stub_value'): TypeInterface
    {
        return parent::fromValue(LowestPriceGroupingType::LOWEST_PRICE);
    }
}