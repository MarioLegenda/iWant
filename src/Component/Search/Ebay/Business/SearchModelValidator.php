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

        $validSortingMethods = ['bestMatch', 'newlyListed'];

        if (in_array($model->getSortingMethod(), $validSortingMethods) === false) {
            $message = sprintf(
                'Invalid sorting method given. Expected %s, got %s',
                implode(', ', $validSortingMethods),
                $model->getSortingMethod()
            );

            throw new \RuntimeException($message);
        }

        if ($model->isNewlyListed() and $model->isBestMatch()) {
            $message = sprintf(
                'Invalid model. %s cannot have two sorting methods set to true',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        if ($model->isWatchCountIncrease() and $model->isWatchCountDecrease()) {
            $message = sprintf(
                'Invalid model. %s cannot have both properties watchCountIncrease and watchCountDecrease set to true',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }
    }
}