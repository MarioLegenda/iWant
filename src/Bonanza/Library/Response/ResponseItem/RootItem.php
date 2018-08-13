<?php

namespace App\Bonanza\Library\Response\ResponseItem;

use App\Bonanza\Library\Response\Type\ResponseType;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Infrastructure\Type\TypeInterface;

class RootItem implements ArrayNotationInterface
{
    /**
     * @var iterable $response
     */
    private $response;
    /**
     * RootItem constructor.
     * @param iterable $response
     */
    public function __construct(iterable $response)
    {
        $this->response = $response;
    }
    /**
     * @return string
     */
    public function getAck(): string
    {
        return $this->response['ack'];
    }
    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->response['version'];
    }
    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->response['timestamp'];
    }
    /**
     * @return TypeInterface|null
     */
    public function getResponseType(): ?TypeInterface
    {
        $types = ResponseType::fromKey('findItemsByKeywordsResponse')->toArray();

        foreach ($types as $typeName => $type) {
            if (array_key_exists($typeName, $this->response)) {
                return ResponseType::fromKey($typeName);
            }
        }
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'ack' => $this->getAck(),
            'version' => $this->getVersion(),
            'timestamp' => $this->getTimestamp(),
            'responseType' => $this->getResponseType()->getKey(),
        ];
    }
}