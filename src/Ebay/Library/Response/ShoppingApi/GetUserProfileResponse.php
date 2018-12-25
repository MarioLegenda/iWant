<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\RootItemInterface;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\ErrorContainer;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\RootItem;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\UserItem;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class GetUserProfileResponse extends BaseResponse implements
    GetUserProfileResponseInterface,
    ArrayNotationInterface
{
    /**
     * Response constructor.
     * @param string $xmlString
     */
    public function __construct(string $xmlString)
    {
        parent::__construct($xmlString);

        $this->responseItems['user'] = null;
    }
    /**
     * @return UserItem
     */
    public function getUser(): UserItem
    {
        $this->lazyLoadSimpleXml($this->xmlString);

        if ($this->responseItems['user'] instanceof UserItem) {
            return $this->responseItems['user'];
        }

        $this->responseItems['user'] = new UserItem($this->simpleXmlBase->User);

        return $this->responseItems['user'];
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        $toArray = array();

        $toArray['response'] = array(
            'rootItem' => $this->getRoot()->toArray(),
            'errors' => ($this->getErrors() instanceof ErrorContainer) ?
                $this->getErrors()->toArray() :
                null,
        );

        return $toArray;
    }
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}