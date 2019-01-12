<?php

namespace App\Component\Search\Ebay\Library\Exception;

class BaseException extends \Exception
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