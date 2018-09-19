<?php

namespace App\Component\Selector\Etsy;

use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Etsy\Presentation\Model\EtsyApiModel;

interface ObserverSelectorInterface
{
    /**
     * @param SubjectSelectorInterface $subject
     * @return EtsyApiModel
     */
    public function update(SubjectSelectorInterface $subject): EtsyApiModel;
}