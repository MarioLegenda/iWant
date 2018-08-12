<?php

namespace App\Web\Model\Request;

use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;

class EbayModels
{
    /**
     * @var FindingApiModel $findingApiModel
     */
    private $findingApiModel;
    /**
     * EbayModels constructor.
     * @param FindingApiModel $findingApiModel
     */
    public function __construct(
        FindingApiModel $findingApiModel
    ) {
        $this->findingApiModel = $findingApiModel;
    }
    /**
     * @return FindingApiModel
     */
    public function getFindingApiModel(): FindingApiModel
    {
        return $this->findingApiModel;
    }

}