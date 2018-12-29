<?php

namespace App\Library\Result;

class NullResult implements ResultInterface
{
    /**
     * @return array
     */
    public function getResult(): iterable
    {
        return [];
    }
}