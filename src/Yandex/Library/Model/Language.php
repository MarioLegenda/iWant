<?php

namespace App\Yandex\Library\Model;

class Language
{
    /**
     * @var string $code
     */
    private $code;
    /**
     * @var string $name
     */
    private $name;
    /**
     * Language constructor.
     * @param string $code
     * @param string $name
     */
    public function __construct(
        string $code,
        string $name
    ) {
        $this->code = $code;
        $this->name = $name;
    }
    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}