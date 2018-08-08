<?php

namespace App\Amazon\Presentation\ProductAdvertising\Model\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class ItemSearchType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'ItemSearch' => 'ItemSearch',
    ];
    /**
     * @param null $value
     * @return TypeInterface
     */
    public static function fromValue($value = null): TypeInterface
    {
        if (!is_null($value)) {
            $message = sprintf(
                'Calling %s::fromValue() has no effect with an argument',
                ItemSearchType::class
            );

            throw new \RuntimeException($message);
        }

        return parent::fromValue('ItemSearch');
    }
}