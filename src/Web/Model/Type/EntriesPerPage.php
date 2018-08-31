<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 30/08/2018
 * Time: 11:09
 */

namespace App\Web\Model\Type;


use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class EntriesPerPage extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'EntriesPerPage',
    ];
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'EntriesPerPage'): TypeInterface
    {
        return parent::fromValue($value);
    }
}