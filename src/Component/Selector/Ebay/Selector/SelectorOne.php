<?php

namespace App\Component\Selector\Ebay\Selector;

use App\Component\Selector\Ebay\ObserverSelectorInterface;
use App\Doctrine\Entity\ApplicationShop;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use SplSubject;

class SelectorOne implements ObserverSelectorInterface
{
    /**
     * @var ApplicationShop $applicationShop
     */
    private $applicationShop;
    /**
     * SelectorOne constructor.
     * @param ApplicationShop $applicationShop
     */
    public function __construct(
        ApplicationShop $applicationShop
    ) {
        $this->applicationShop = $applicationShop;
    }
    /**
     * @param SplSubject $subject
     * @return FindingApiModel|null
     */
    public function update(SplSubject $subject): ?FindingApiModel
    {

    }
}