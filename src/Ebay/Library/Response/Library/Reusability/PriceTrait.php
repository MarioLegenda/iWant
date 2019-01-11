<?php

namespace App\Ebay\Library\Response\Library\Reusability;

use App\Ebay\Library\Response\ShoppingApi\Json\BasePrice;

trait PriceTrait
{
    /**
     * @param string $propertyName
     * @return BasePrice|null
     */
    protected function insurePrice(string $propertyName): ?BasePrice
    {

        if ($this->{$propertyName} instanceof BasePrice) {
            return $this->{$propertyName};
        }

        if (empty($this->{$propertyName})) {
            return null;
        }

        $this->{$propertyName} = new BasePrice(
            $this->{$propertyName}['CurrencyID'],
            $this->{$propertyName}['Value']
        );

        return $this->{$propertyName};
    }
}