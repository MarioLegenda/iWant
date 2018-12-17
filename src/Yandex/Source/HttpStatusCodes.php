<?php

namespace App\Yandex\Source;

class HttpStatusCodes
{
    /**
     * @var array $statusCodes
     */
    private static $statusCodes = [
        200 => 'OK',
        401 => 'InvalidApiKey',
        402 => 'BlockedApiKey',
        404 => 'DailyLimitExceeded',
        413 => 'MaximumTextSize',
        422 => 'TextCannotBeTranslated',
        501 => 'TranslationDirectionNotSupported',
    ];
    /**
     * @param int $statusCode
     * @return bool
     */
    public static function isSuccess(int $statusCode): bool
    {
        return array_key_exists($statusCode, static::$statusCodes);
    }
    /**
     * @param int $statusCode
     * @return bool
     */
    public static function isFailure(int $statusCode): bool
    {
        return $statusCode !== 200;
    }
    /**
     * @param int $statusCode
     * @return string|null
     */
    public static function getName(int $statusCode): ?string
    {
        if (!array_key_exists($statusCode, static::$statusCodes)) {
            return 'Unknown';
        }

        return static::$statusCodes[$statusCode];
    }
}