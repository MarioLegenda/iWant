<?php

namespace App\Component\Request\Model;

class TodayProduct
{
    /**
     * @var \DateTime $storedAt
     */
    private $storedAt;
    /**
     * TodayProduct constructor.
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