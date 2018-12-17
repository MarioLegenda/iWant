<?php

namespace App\Ebay\Library\Exception;

class BaseEbayException extends \Exception
{
    /**
     * BaseEbayException constructor.
     * @param string $message
     */
    public function __construct(string $message = "")
    {
        parent::__construct($message);
    }
}