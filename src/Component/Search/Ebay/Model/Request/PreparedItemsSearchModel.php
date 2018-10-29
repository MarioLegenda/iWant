<?php

namespace App\Component\Search\Ebay\Model\Request;

class PreparedItemsSearchModel
{
    /**
     * @var string $uniqueName
     */
    private $uniqueName;
    /**
     * PreparedItemsSearchModel constructor.
     * @param string $uniqueName
     */
    public function __construct(
        string $uniqueName
    ) {
        $this->uniqueName = $uniqueName;
    }
    /**
     * @return string
     */
    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }
}