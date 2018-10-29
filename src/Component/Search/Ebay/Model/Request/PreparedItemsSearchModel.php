<?php

namespace App\Component\Search\Ebay\Model\Request;

class PreparedItemsSearchModel
{
    /**
     * @var string $uniqueName
     */
    private $uniqueName;
    /**
     * @var Pagination $pagination
     */
    private $pagination;
    /**
     * PreparedItemsSearchModel constructor.
     * @param string $uniqueName
     * @param Pagination $pagination
     */
    public function __construct(
        string $uniqueName,
        Pagination $pagination
    ) {
        $this->uniqueName = $uniqueName;
        $this->pagination = $pagination;
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
}