<?php

namespace App\Web\Library\View;

use App\Library\Infrastructure\Type\BaseType;

class EbaySearchViewType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'globalIdView',
        'itemsView',
        'default',
    ];
}