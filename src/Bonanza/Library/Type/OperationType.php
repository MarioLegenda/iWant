<?php

namespace App\Bonanza\Library\Type;

use App\Library\Infrastructure\Type\BaseType;

class OperationType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'FindItemsByKeywords' => 'findItemsByKeywords',
    ];
}