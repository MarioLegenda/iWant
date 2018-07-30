<?php

namespace App\Library\Validation;

interface ValidatorInterface
{
    /**
     * @param $value
     * @param array $additionalData
     * @return mixed
     */
    public function validate($value, array $additionalData = []);
}