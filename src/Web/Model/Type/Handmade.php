<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 28/08/2018
 * Time: 11:48
 */

namespace App\Web\Model\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class Handmade extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'Handmade',
    ];
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'Handmade'): TypeInterface
    {
        return parent::fromValue($value);
    }
}