<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class RootItem extends AbstractItem implements ResponseItemInterface, ArrayNotationInterface
{
    /**
     * @var string $searchResultsCount
     */
    private $searchResultsCount;
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
            $this->setVersion((string) $this->simpleXml->version);
        }

        return $this->version;
    }
    /**
     * @return string
     */
    public function getAck() : string
    {
        if ($this->ack === null) {
            $this->setAck((string) $this->simpleXml->ack);
        }

        return $this->ack;
    }
    /**
     * @return string
     */
    public function getTimestamp() : string
    {
        if ($this->timestamp === null) {
            $this->setTimestamp((string) $this->simpleXml->timestamp);
        }

        return $this->timestamp;
    }
    /**
     * @return int
     */
    public function getSearchResultsCount() : int
    {
        if ($this->searchResultsCount === null) {
            $this->setSearchResultsCount((int) $this->simpleXml->searchResult['count']);
        }

        return $this->searchResultsCount;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'ack' => $this->getAck(),
            'namespace' => $this->getNamespace(),
            'searchResultsCount' => $this->getSearchResultsCount(),
            'version' => $this->getVersion(),
            'timestamp' => $this->getTimestamp(),
        );
    }

    private function setAck(string $ack) : RootItem
    {
        $this->ack = $ack;

        return $this;
    }

    private function setTimestamp(string $timestamp) : RootItem
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    private function setSearchResultsCount(string $count) : RootItem
    {
        $this->searchResultsCount = $count;

        return $this;
    }

    private function setNamespace(string $namespace) : RootItem
    {
        $this->itemNamespace = $namespace;

        return $this;
    }

    private function setVersion(string $version) : RootItem
    {
        $this->version = $version;

        return $this;
    }
}