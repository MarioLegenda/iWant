<?php

namespace App\Library\Result;

class ResultFactory
{
    /**
     * @param iterable|null $result
     * @return ResultInterface
     */
    public static function createResult($result): ResultInterface
    {
        if (is_null($result)) {
            return new NullResult();
        }

        if (is_iterable($result) and empty($result)) {
            return new EmptyResult();
        }

        if (is_iterable($result) and !empty($result)) {
            return new Result($result);
        }

        return new NullResult();
    }
}