<?php

namespace App\Component\Search\Ebay\Business;

use App\Component\Search\Ebay\Model\Request\Pagination;

class PaginationHandler
{
    /**
     * @param array $listing
     * @param Pagination $pagination
     * @return array
     */
    public function paginateListing(array $listing, Pagination $pagination): array
    {
        $paginationIndexes = $this->createPaginationIndexes($pagination);
        $firstIndex = $paginationIndexes['firstIndex'];
        $lastIndex = $paginationIndexes['lastIndex'];

        $paginatedListing = [];
        for ($i = $firstIndex; $i <= $lastIndex; $i++) {
            $paginatedListing[] = $listing[$i];
        }

        return $paginatedListing;
    }
    /**
     * @param Pagination $pagination
     * @return array
     */
    private function createPaginationIndexes(Pagination $pagination)
    {
        return [
            'firstIndex' => ($pagination->getPage() * $pagination->getLimit()) - $pagination->getLimit(),
            'lastIndex' => ($pagination->getPage() * $pagination->getLimit()) - 1,
        ];
    }
}