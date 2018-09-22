<?php

namespace App\Component\TodayProducts\Selector\Ebay;

use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;

interface ObserverSelectorInterface
{
    /**
     * @param SubjectSelectorInterface $subject
     * @return FindingApiModel|null
     */
    public function update(SubjectSelectorInterface $subject): ?FindingApiRequestModelInterface;
}