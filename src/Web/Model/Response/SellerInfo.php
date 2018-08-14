<?php

namespace App\Web\Model\Response;

class SellerInfo
{
    /**
     * @var string $userName
     */
    private $userName;
    /**
     * SellerInfo constructor.
     * @param string $userName
     */
    public function __construct(
        string $userName
    ) {
        $this->userName = $userName;
    }
    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }
}