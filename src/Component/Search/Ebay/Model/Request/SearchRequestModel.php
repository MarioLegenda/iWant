<?php

namespace App\Component\Search\Ebay\Model\Request;

use App\Component\Search\Ebay\Business\Factory\Metadata\RootMetadata;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;

class SearchRequestModel
{
    /**
     * @var FindingApiModel $entryPointModel
     */
    private $entryPointModel;
    /**
     * @var RootMetadata $metadata
     */
    private $metadata;
    /**
     * SearchRequestModel constructor.
     * @param RootMetadata $metadata
     * @param FindingApiModel $model
     */
    public function __construct(
        RootMetadata $metadata,
        FindingApiModel $model
    ) {
        $this->metadata = $metadata;
        $this->entryPointModel = $model;
    }
    /**
     * @return RootMetadata
     */
    public function getMetadata(): RootMetadata
    {
        return $this->metadata;
    }
    /**
     * @return FindingApiModel
     */
    public function getEntryPointModel(): FindingApiModel
    {
        return $this->entryPointModel;
    }
}