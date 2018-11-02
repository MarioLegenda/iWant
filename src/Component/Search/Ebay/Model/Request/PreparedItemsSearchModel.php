<?php

namespace App\Component\Search\Ebay\Model\Request;

class PreparedItemsSearchModel
{
    /**
     * @var string $uniqueName
     */
    private $uniqueName;
    /**
     * @var string $globalId
     */
    private $globalId;
    /**
     * @var string $locale
     */
    private $locale;
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
     * @param string $globalId
     * @param string $locale
     * @param Pagination $pagination
     * @param bool $lowestPrice
     */
    public function __construct(
        string $uniqueName,
        string $globalId,
        string $locale,
        bool $lowestPrice,
        Pagination $pagination
    ) {
        $this->uniqueName = $uniqueName;
        $this->globalId = $globalId;
        $this->locale = $locale;
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
    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }
    /**
     * @return string
     */
    public function getGlobalId(): string
    {
        return $this->globalId;
    }
}