<?php

namespace App\Web\Model\Response\Type;

use App\Library\Infrastructure\Type\BaseType;

class DeferrableType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'concrete_object',
        'http_deferrable',
    ];
}