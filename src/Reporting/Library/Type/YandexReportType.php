<?php

namespace App\Reporting\Library\Type;

use App\Library\Infrastructure\Type\BaseType;
use App\Library\Infrastructure\Type\TypeInterface;

class YandexReportType extends BaseType
{
    /**
     * @var array $type
     */
    protected static $types = [
        'yandex_translation_report',
    ];
    /**
     * @param string $value
     * @return TypeInterface
     */
    public static function fromValue($value = 'yandex_translation_report'): TypeInterface
    {
        if ($value !== static::$types[0]) {
            $message = sprintf(
                'Invalid type given for %s. The value should be %s',
                get_class(YandexReportType::class),
                static::$types[0]
            );

            throw new \RuntimeException($message);
        }

        return parent::fromValue($value);
    }
}