<?php

namespace App\Component\Request\Model;

class TodayProduct
{
    /**
     * @var \DateTime $dateTime
     */
    private $todayDateTime;
    /**
     * TodayProduct constructor.
     * @param \DateTime $todayDateTime
     */
    public function __construct(
        \DateTime $todayDateTime
    ) {
        $this->todayDateTime = $todayDateTime;
    }
}