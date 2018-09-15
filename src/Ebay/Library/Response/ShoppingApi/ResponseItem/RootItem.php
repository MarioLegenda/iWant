<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

use App\Ebay\Library\Response\RootItemInterface;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class RootItem extends AbstractItem implements
    ResponseItemInterface,
    RootItemInterface,
    ArrayNotationInterface
{
    /**
     * @var string $timestamp
     */
    private $timestamp;
    /**
     * @var string $ack
     */
    private $ack;
    /**
     * @var string $version
     */
    private $version;
    /**
     * @var string $itemNamespace
     */
    private $itemNamespace;
    /**
     * @return string
     */
    public function getNamespace() : string
    {
        if ($this->itemNamespace === null) {
            $docNamespace = $this->simpleXml->getDocNamespaces();

            $this->setNamespace($docNamespace[array_keys($docNamespace)[0]]);
        }

        return $this->itemNamespace;
    }
    /**
     * @return string
     */
    public function getVersion() : string
    {
        if ($this->version === null) {
            $this->setVersion((string) $this->simpleXml->Version);
        }

        return $this->version;
    }
    /**
     * @return string
     */
    public function getAck() : string
    {
        if ($this->ack === null) {
            $this->setAck((string) $this->simpleXml->Ack);
        }

        return $this->ack;
    }
    /**
     * @return string
     */
    public function getTimestamp() : string
    {
        if ($this->timestamp === null) {
            $this->setTimestamp((string) $this->simpleXml->Timestamp);
        }

        return $this->timestamp;
    }
    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return strtolower($this->getAck()) !== 'Failure';
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'ack' => $this->getAck(),
            'namespace' => $this->getNamespace(),
            'version' => $this->getVersion(),
            'timestamp' => $this->getTimestamp(),
        );
    }
    /**
     * @param string $ack
     * @return RootItem
     */
    private function setAck(string $ack) : RootItem
    {
        $this->ack = $ack;

        return $this;
    }
    /**
     * @param string $timestamp
     * @return RootItem
     */
    private function setTimestamp(string $timestamp) : RootItem
    {
        $this->timestamp = $timestamp;

        return $this;
    }
    /**
     * @param string $namespace
     * @return RootItem
     */
    private function setNamespace(string $namespace) : RootItem
    {
        $this->itemNamespace = $namespace;

        return $this;
    }
    /**
     * @param string $version
     * @return RootItem
     */
    private function setVersion(string $version) : RootItem
    {
        $this->version = $version;

        return $this;
    }
}