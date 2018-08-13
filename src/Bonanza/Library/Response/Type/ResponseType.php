<?php

namespace App\Bonanza\Library\Response\Type;

use App\Library\Infrastructure\Type\BaseType;

class ResponseType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'findItemsByKeywordsResponse' => 'FindItemsByKeywordsResponse',
    ];
}