<?php

namespace App\Library\Http\Request\Type;

use App\Library\Infrastructure\Type\BaseType;

class InternalTypeType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'paginated_view',
        'paginated_internalized_view',
        'view',
        'creation',
        'update',
    ];
}