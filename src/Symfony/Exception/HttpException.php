<?php

namespace App\Symfony\Exception;

use Throwable;

class HttpException extends \Exception
{
    /**
     * @var \Exception $wrappedException
     */
    private $wrappedException;
    /**
     * HttpException constructor.
     * @param \Exception $wrappedException
     */
    public function __construct(\Exception $wrappedException)
    {
        $this->wrappedException = $wrappedException;

        parent::__construct($wrappedException->getMessage());
    }


}