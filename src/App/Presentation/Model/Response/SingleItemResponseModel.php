<?php

namespace App\App\Presentation\Model\Response;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class SingleItemResponseModel implements ArrayNotationInterface
{
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * @var array $response
     */
    private $response;
    /**
     * SingleItemResponseModel constructor.
     * @param string $itemId
     * @param array $response
     */
    public function __construct(
        string $itemId,
        array $response
    ) {
        $this->itemId = $itemId;
        $this->response = $response;
    }
    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }
    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'itemId' => $this->getItemId(),
            'singleItem' => $this->getResponse(),
        ];
    }
}