<?php

namespace App\Component\Search\Ebay\Library\Exception;

use App\Component\Search\Ebay\Model\Request\InternalSearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Component\Search\Ebay\Model\Request\SearchModelInterface;

class EbayEmptyResultException extends BaseException
{
    /**
     * EbayEmptyResultException constructor.
     * @param SearchModelInterface|SearchModel|InternalSearchModel $model
     */
    public function __construct(SearchModelInterface $model)
    {
        parent::__construct(json_encode($model->toArray()));
    }
}