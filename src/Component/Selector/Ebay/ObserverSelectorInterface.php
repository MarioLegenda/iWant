<?php

namespace App\Component\Selector\Ebay;

use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;

interface ObserverSelectorInterface extends \SplObserver
{
    /**
     * @param \SplSubject $subject
     * @return FindingApiModel|null
     */
    public function update(\SplSubject $subject): ?FindingApiRequestModelInterface;
}