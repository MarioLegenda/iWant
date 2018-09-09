<?php

namespace App\Component\Selector\Etsy;

use App\Etsy\Presentation\Model\EtsyApiModel;

interface ObserverSelectorInterface extends \SplObserver
{
    /**
     * @param \SplSubject $subject
     * @return EtsyApiModel
     */
    public function update(\SplSubject $subject): EtsyApiModel;
}