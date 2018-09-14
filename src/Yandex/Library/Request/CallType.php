<?php

namespace App\Yandex\Library\Request;

use App\Library\Infrastructure\Type\BaseType;

class CallType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'getLangs' => 'getLangs',
        'detect' => 'detect',
    ];
}