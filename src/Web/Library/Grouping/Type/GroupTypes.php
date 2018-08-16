<?php

namespace App\Web\Library\Grouping\Type;

class GroupTypes
{
    /**
     * @return iterable
     */
    public static function getGroupTypes(): iterable
    {
        return [
            'lowest_price' => LowestPriceGroupingType::class,
            'highest_price' => HighestPriceGroupingType::class,
            'highest_quality' => '',
            'start_date_nearest' => '',
            'start_date_farthest' => '',
            'end_date_nearest' => '',
            'end_date_farthest' => '',
        ];
    }
}