<?php

namespace App\Component\Search\Ebay\Model\Request;

class PreparedItemsSearchModel
{
    /**
     * @var string $uniqueName
     */
    private $uniqueName;
    /**
     * @var bool $lowestPrice
     */
    private $lowestPrice;
    /**
     * @var Pagination $pagination
     */
    private $pagination;
    /**
     * PreparedItemsSearchModel constructor.
     * @param string $uniqueName
     * @param Pagination $pagination
     * @param bool $lowestPrice
     */
    public function __construct(
        string $uniqueName,
        bool $lowestPrice,
        Pagination $pagination
    ) {
        $this->uniqueName = $uniqueName;
        $this->pagination = $pagination;
        $this->lowestPrice = $lowestPrice;
    }
    /**
     * @return string
     */
    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }
    /**
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        return $this->pagination;
    }
    /**
     * @return bool
     */
    public function isLowestPrice(): bool
    {
        return $this->lowestPrice;
    }
}