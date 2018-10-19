<?php

namespace App\Web\Model\Request;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ActivityMessage implements ArrayNotationInterface
{
    /**
     * @var string $message;
     */
    private $message;
    /**
     * @var string $additionalData
     */
    private $additionalData;
    /**
     * ActivityMessage constructor.
     * @param string $message
     * @param string $additionalData
     */
    public function __construct(
        string $message,
        string $additionalData
    ) {
        $this->message = $message;
        $this->additionalData = $additionalData;
    }
    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
    /**
     * @return string
     */
    public function getAdditionalData(): string
    {
        return $this->additionalData;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'message' => $this->getMessage(),
            'additionalData' => $this->getAdditionalData(),
        ];
    }
}