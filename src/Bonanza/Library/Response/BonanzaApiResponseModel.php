<?php

namespace App\Bonanza\Library\Response;

use App\Bonanza\Library\Response\ResponseItem\FindItemsByKeywordsResponse;
use App\Bonanza\Library\Response\ResponseItem\RootItem;
use App\Library\Infrastructure\Type\TypeInterface;

class BonanzaApiResponseModel implements BonanzaApiResponseModelInterface
{
    /**
     * @var iterable $response
     */
    private $response;
    /**
     * @var array $responseObjects
     */
    private $responseObjects = [
        'rootItem' => null,
        'findItemsByKeywordsResponse' => null,
    ];
    /**
     * BonanzaApiResponseModel constructor.
     * @param iterable $response
     */
    public function __construct(iterable $response)
    {
        $this->response = $response;
    }
    /**
     * @return RootItem
     */
    public function getRootItem(): RootItem
    {
        if (!$this->responseObjects['rootItem'] instanceof RootItem) {
            $this->responseObjects['rootItem'] = new RootItem($this->response);
        }

        return $this->responseObjects['rootItem'];
    }
    /**
     * @return FindItemsByKeywordsResponse|null
     */
    public function getFindItemsByKeywordsResponse(): ?FindItemsByKeywordsResponse
    {
        if (!$this->responseObjects['findItemsByKeywordsResponse'] instanceof FindItemsByKeywordsResponse) {
            $rootItem = $this->getRootItem();

            $responseType = $rootItem->getResponseType();

            if (!$responseType instanceof TypeInterface) {
                $message = sprintf(
                    '%s could not determine the type of response. This has to happen',
                    get_class($this)
                );

                throw new \RuntimeException($message);
            }

            $findItemsByKeywordsResponseData = $this->response[$responseType->getKey()];

            $this->responseObjects['findItemsByKeywordsResponse'] =
                new FindItemsByKeywordsResponse($findItemsByKeywordsResponseData);
        }

        return $this->responseObjects['findItemsByKeywordsResponse'];
    }
}