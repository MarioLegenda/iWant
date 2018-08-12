<?php

namespace App\Bonanza\Library\Response;

use App\Bonanza\Library\Response\ResponseItem\FindItemsByKeywordsResponse;
use App\Bonanza\Library\Response\ResponseItem\RootItem;

interface BonanzaApiResponseModelInterface
{
    /**
     * @return RootItem
     */
    public function getRootItem(): RootItem;
    /**
     * @return FindItemsByKeywordsResponse|null
     */
    public function getFindItemsByKeywordsResponse(): ?FindItemsByKeywordsResponse;
}