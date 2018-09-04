<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 04/09/2018
 * Time: 19:22
 */

namespace App\Library;


use App\Library\Infrastructure\Type\BaseType;

class MarketplaceType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'Ebay',
        'Amazon',
        'Etsy',
    ];
}