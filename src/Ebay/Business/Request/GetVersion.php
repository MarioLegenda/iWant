<?php

namespace App\Ebay\Business\Request;

use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Processor\FindingApiRequestBaseProcessor;

class GetVersion extends FindingApiRequestMetadataProducer
{
    /**
     * GetVersion constructor.
     * @param FindingApiRequestModelInterface $model
     * @param FindingApiRequestBaseProcessor $requestBaseProcessor
     */
    public function __construct(FindingApiRequestModelInterface $model, FindingApiRequestBaseProcessor $requestBaseProcessor)
    {
        $this->validate($model);

        parent::__construct($model, $requestBaseProcessor);
    }
    /**
     * @param FindingApiRequestModelInterface $model
     */
    private function validate(FindingApiRequestModelInterface $model)
    {
        if ($model->hasItemFilters()) {
            $message = sprintf(
                'GetVersion request cannot have any item filters'
            );

            throw new \RuntimeException($message);
        }
    }
}