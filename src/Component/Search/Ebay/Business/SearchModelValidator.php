<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Model\Request\SearchModel;

class SearchModelValidator
{
    /**
     * @param SearchModel $model
     */
    public function validate(SearchModel $model)
    {
        if ($model->isLowestPrice() and $model->isHighestPrice()) {
            $message = sprintf(
                'Invalid model. %s cannot have lowest price and highest price set to true',
                get_class($model)
            );

            throw new \RuntimeException($message);
        }
    }
}