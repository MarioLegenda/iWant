<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

class ItemSpecifics extends AbstractItem
{
    /**
     * @var NameValueList $nameValueList
     */
    private $nameValueList;
    /**
     * @param null $default
     * @return NameValueList
     */
    public function getNameValueList($default = null): ?NameValueList
    {
        if (!$this->nameValueList instanceof NameValueList) {
            if (!empty($this->simpleXml->NameValueList)) {
                $this->nameValueList = new NameValueList($this->simpleXml->NameValueList);
            }
        }

        if ($this->nameValueList === null and $default !== null) {
            return $default;
        }

        return $this->nameValueList;
    }
}