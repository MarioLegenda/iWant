<?php

namespace App\Bonanza\Presentation\Model;

interface CallTypeInterface
{
    /**
     * @return string
     */
    public function getOperationName(): string;
    /**
     * @return string
     */
    public function getQueryName(): string;
    /**
     * @return string
     */
    public function getQueryValue(): string;
}