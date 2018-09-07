<?php

namespace App\Component\TodayProducts;

use App\Ebay\Business\Finder as EbayFinder;
use App\Etsy\Business\Finder as EtsyFinder;

class TodayProductsComponent
{
    /**
     * @var EbayFinder $ebayFinder
     */
    private $ebayFinder;
    /**
     * @var EtsyFinder $etsyFinder
     */
    private $etsyFinder;
    /**
     * TodayProductsComponent constructor.
     * @param EbayFinder $ebayFinder
     * @param EtsyFinder $etsyFinder
     */
    public function __construct(
        EbayFinder $ebayFinder,
        EtsyFinder $etsyFinder
    ) {
        $this->ebayFinder = $ebayFinder;
        $this->etsyFinder = $etsyFinder;
    }


}