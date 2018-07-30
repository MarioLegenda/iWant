<?php

namespace App\Library\Http\Request\Type;

use App\Library\Infrastructure\Type\StringType;

class PostHttpType extends StringType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'post',
    ];
}