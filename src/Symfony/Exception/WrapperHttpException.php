<?php

namespace App\Symfony\Exception;


class WrapperHttpException extends \Exception
{
    /**
     * @var \Exception $wrappedException
     */
    private $wrappedException;
    /**
     * WrapperHttpException constructor.
     * @param \Exception $wrappedException
     */
    public function __construct(\Exception $wrappedException)
    {
        $this->wrappedException = $wrappedException;

        parent::__construct($wrappedException->getMessage());
    }


}