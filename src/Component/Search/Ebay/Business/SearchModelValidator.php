<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Business\Filter\SortingMethod;
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

        $validSortingMethods = [
            SortingMethod::BEST_MATCH,
            SortingMethod::NEWLY_LISTED,
            SortingMethod::WATCH_COUNT_INCREASE,
            SortingMethod::WATCH_COUNT_DECREASE
        ];

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
    }
}