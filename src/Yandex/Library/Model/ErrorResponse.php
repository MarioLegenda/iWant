<?php

namespace App\Yandex\Library\Model;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ErrorResponse implements ArrayNotationInterface
{
    /**
     * @var array $response
     */
    private $response;
    /**
     * @var bool $invalidApiKey
     */
    private $invalidApiKey;
    /**
     * @var bool $isBlockedApiKey
     */
    private $blockedApiKey;
    /**
     * @var bool $isDailyLimitExceeded
     */
    private $dailyLimitExceeded;
    /**
     * @var bool $unhandledError
     */
    private $unhandledError;
    /**
     * ErrorResponse constructor.
     * @param array $response
     * @param bool|bool $invalidApiKey
     * @param bool|bool $isBlockedApiKey
     * @param bool|bool $isDailyLimitExceeded
     * @param bool|bool $unhandledError
     */
    public function __construct(
        array $response,
        bool $invalidApiKey = false,
        bool $isBlockedApiKey = false,
        bool $isDailyLimitExceeded = false,
        bool $unhandledError = false
    ) {
        $this->response = $response;
        $this->invalidApiKey = $invalidApiKey;
        $this->blockedApiKey = $isBlockedApiKey;
        $this->dailyLimitExceeded = $isDailyLimitExceeded;
        $this->unhandledError = $unhandledError;
    }
    /**
     * @return bool
     */
    public function isInvalidApiKey(): bool
    {
        return $this->invalidApiKey;
    }
    /**
     * @return bool
     */
    public function isBlockedApiKey(): bool
    {
        return $this->blockedApiKey;
    }
    /**
     * @return bool
     */
    public function isDailyLimitExceeded(): bool
    {
        return $this->dailyLimitExceeded;
    }

    public function isUnhandledError(): bool
    {
        return $this->unhandledError;
    }
    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->response['code'];
    }
    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->response['message'];
    }

    public function invalidApiKey(): void
    {
        $this->invalidApiKey = true;
    }

    public function blockedApiKey(): void
    {
        $this->blockedApiKey = true;
    }

    public function dailyLimitExceeded(): void
    {
        $this->dailyLimitExceeded = true;
    }

    public function unhandledError(): void
    {
        $this->unhandledError = true;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'isInvalidApiKey' => $this->isInvalidApiKey(),
            'isBlockedApiKey' => $this->isBlockedApiKey(),
            'isDailyLimitExceeded' => $this->isDailyLimitExceeded(),
            'isUnhandledError' => $this->isUnhandledError(),
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
        ];
    }
}