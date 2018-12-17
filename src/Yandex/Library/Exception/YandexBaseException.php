<?php

namespace App\Yandex\Library\Exception;

class YandexBaseException extends \Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message);
    }
}