<?php

namespace App\Component\TodayProducts\Request\Model;

class TodayProduct
{
    /**
     * @var \DateTime $storedAt
     */
    private $storedAt;
    /**
     * TodayProductRequestModel constructor.
     * @param \DateTime $storedAt
     */
    public function __construct(
        \DateTime $storedAt
    ) {
        $this->storedAt = $storedAt;
    }
    /**
     * @return \DateTime
     */
    public function getStoredAt(): \DateTime
    {
        return $this->storedAt;
    }
}