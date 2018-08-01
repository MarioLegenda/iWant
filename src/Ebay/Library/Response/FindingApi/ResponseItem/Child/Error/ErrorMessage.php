<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Error;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\ResponseItem\AbstractItemIterator;

class ErrorMessage extends AbstractItemIterator implements ArrayNotationInterface
{
    /**
     * @var string $subdomain
     */
    private $subdomain;
    /**
     * @var string $severity
     */
    private $severity;
    /**
     * @var string $message
     */
    private $message;
    /**
     * @var string $exceptionId
     */
    private $exceptionId;
    /**
     * @var int $errorId
     */
    private $errorId;
    /**
     * @var string $domain
     */
    private $domain;
    /**
     * @var string $category
     */
    private $category;
    /**
     * ErrorMessage constructor.
     * @param \SimpleXMLElement $simpleXML
     */
    public function __construct(\SimpleXMLElement $simpleXML)
    {
        parent::__construct($simpleXML);

        $this->loadParameters($simpleXML);
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getCategory($default = null)
    {
        if ($this->category === null) {
            if (!empty($this->simpleXml->error->category)) {
                $this->setCategory((string) $this->simpleXml->error->category);
            }
        }

        if ($this->category === null and $default !== null) {
            return $default;
        }

        return $this->category;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getDomain($default = null)
    {
        if ($this->domain === null) {
            if (!empty($this->simpleXml->error->domain)) {
                $this->setDomain((string) $this->simpleXml->error->domain);
            }
        }

        if ($this->domain === null and $default !== null) {
            return $default;
        }

        return $this->domain;
    }
    /**
     * @param null $default
     * @return int|null
     */
    public function getErrorId($default = null)
    {
        if ($this->errorId === null) {
            if (!empty($this->simpleXml->error->errorId)) {
                $this->setErrorId((int) $this->simpleXml->error->errorId);
            }
        }

        if ($this->errorId === null and $default !== null) {
            return $default;
        }

        return $this->errorId;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getExceptionId($default = null)
    {
        if ($this->exceptionId === null) {
            if (!empty($this->simpleXml->error->exceptionId)) {
                $this->setExceptionId((string) $this->simpleXml->error->exceptionId);
            }
        }

        if ($this->exceptionId === null and $default !== null) {
            return $default;
        }

        return $this->exceptionId;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getMessage($default = null)
    {
        if ($this->message === null) {
            if (!empty($this->simpleXml->error->message)) {
                $this->setMessage((string) $this->simpleXml->error->message);
            }
        }

        if ($this->message === null and $default !== null) {
            return $default;
        }

        return $this->message;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getSeverity($default = null)
    {
        if ($this->severity === null) {
            if (!empty($this->simpleXml->error->severity)) {
                $this->setSeverity((string) $this->simpleXml->error->severity);
            }
        }

        if ($this->severity === null and $default) {
            return $default;
        }

        return $this->severity;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getSubdomain($default = null)
    {
        if ($this->subdomain === null) {
            if (!empty($this->simpleXml->error->subdomain)) {
                $this->setSubdomain((string) $this->simpleXml->error->subdomain);
            }
        }

        if ($this->subdomain === null and $default !== null) {
            return $default;
        }

        return $this->subdomain;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'category' => $this->getCategory(),
            'domain' => $this->getDomain(),
            'errorId' => $this->getErrorId(),
            'exceptionId' => $this->getExceptionId(),
            'message' => $this->getMessage(),
            'severity' => $this->getSeverity(),
            'subdomain' => $this->getSubdomain(),
        );
    }

    private function loadParameters(\SimpleXMLElement $simpleXml)
    {
        if (!empty($simpleXml->error->parameter)) {
            foreach ($simpleXml->error->parameter as $parameter) {
                $this->addItem(new Parameter($parameter));
            }
        }
    }

    private function setCategory(string $category) : ErrorMessage
    {
        $this->category = $category;

        return $this;
    }

    private function setDomain(string $domain) : ErrorMessage
    {
        $this->domain = $domain;

        return $this;
    }

    private function setErrorId(int $errorId) : ErrorMessage
    {
        $this->errorId = $errorId;

        return $this;
    }

    private function setExceptionId(string $exceptionId) : ErrorMessage
    {
        $this->exceptionId = $exceptionId;

        return $this;
    }

    private function setMessage(string $message) : ErrorMessage
    {
        $this->message = $message;

        return $this;
    }

    private function setSeverity(string $severity) : ErrorMessage
    {
        $this->severity = $severity;

        return $this;
    }

    private function setSubdomain(string $subdomain) : ErrorMessage
    {
        $this->subdomain = $subdomain;

        return $this;
    }
}