<?php

namespace App\Library\Result;

interface ResultInterface
{
    /**
     * @return iterable
     */
    public function getResult(): iterable;
}