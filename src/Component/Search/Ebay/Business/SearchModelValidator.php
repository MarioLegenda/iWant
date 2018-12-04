<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;

class SearchModelValidator
{
    /**
     * @param SearchModel|SearchModelInterface $model
     */
    public function validate(SearchModelInterface $model)
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