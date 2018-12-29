<?php

namespace App\Library\Result;

class EmptyResult implements ResultInterface
{
    /**
     * @return array
     */
    public function getResult(): iterable
    {
        return [];
    }
}