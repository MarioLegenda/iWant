<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json;

class Error
{
    /**
     * @var string $shortMessage
     */
    private $shortMessage;
    /**
     * @var string $longMessage
     */
    private $longMessage;
    /**
     * @var string $errorCode
     */
    private $errorCode;
    /**
     * @var string $severityCode
     */
    private $severityCode;
    /**
     * @var string $errorClassification
     */
    private $errorClassification;
    /**
     * Error constructor.
     * @param string $shortMessage
     * @param string $longMessage
     * @param string $errorCode
     * @param string $severityCode
     * @param string $errorClassification
     */
    public function __construct(
        string $shortMessage,
        string $longMessage,
        string $errorCode,
        string $severityCode,
        string $errorClassification
    ) {
        $this->shortMessage = $shortMessage;
        $this->longMessage = $longMessage;
        $this->errorCode = $errorCode;
        $this->severityCode = $severityCode;
        $this->errorClassification = $errorClassification;
    }
    /**
     * @return string
     */
    public function getShortMessage(): string
    {
        return $this->shortMessage;
    }
    /**
     * @return string
     */
    public function getLongMessage(): string
    {
        return $this->longMessage;
    }
    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
    /**
     * @return string
     */
    public function getSeverityCode(): string
    {
        return $this->severityCode;
    }
    /**
     * @return string
     */
    public function getErrorClassification(): string
    {
        return $this->errorClassification;
    }
}