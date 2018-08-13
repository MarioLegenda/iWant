<?php

namespace App\Web\Model\Request;

use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;

class EbayModels
{
    /**
     * @var FindingApiResponseModelInterface $findingApiModel
     */
    private $findingApiRequestModel;
    /**
     * EbayModels constructor.
     * @param FindingApiRequestModelInterface $findingApiRequestModel
     */
    public function __construct(
        FindingApiRequestModelInterface $findingApiRequestModel
    ) {
        $this->findingApiRequestModel = $findingApiRequestModel;
    }
    /**
     * @return FindingApiRequestModelInterface
     */
    public function getFindingApiModel(): FindingApiRequestModelInterface
    {
        return $this->findingApiRequestModel;
    }

}