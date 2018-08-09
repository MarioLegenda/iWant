<?php

namespace App\Bonanza\Library\Type;

use App\Library\Infrastructure\Type\BaseType;

class ResponseDataFormatType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'xml' => 'XML',
        'json' => 'JSON',
    ];
}