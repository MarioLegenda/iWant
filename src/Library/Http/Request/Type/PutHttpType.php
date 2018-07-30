<?php

namespace App\Library\Http\Request\Type;

use App\Library\Infrastructure\Type\StringType;

class PutHttpType extends StringType
{
    /**
     * @var array $types
     */
    protected $types = [
        'put',
    ];
}