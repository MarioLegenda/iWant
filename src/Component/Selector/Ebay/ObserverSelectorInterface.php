<?php

namespace App\Component\Selector\Ebay;

use App\Component\Request\Model\TodayProduct;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;

interface ObserverSelectorInterface extends \SplObserver
{
    /**
     * @param \SplSubject $subject
     * @return FindingApiModel|null
     */
    public function update(\SplSubject $subject): ?FindingApiModel;
}