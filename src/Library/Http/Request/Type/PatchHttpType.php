<?php

namespace App\Library\Http\Request\Type;

use App\Library\Infrastructure\Type\StringType;

class PatchHttpType extends StringType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'patch',
    ];
}