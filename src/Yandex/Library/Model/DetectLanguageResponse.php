<?php

namespace App\Yandex\Library\Model;

class DetectLanguageResponse
{
    /**
     * @var int $code
     */
    private $code;
    /**
     * @var string $lang
     */
    private $lang;
    /**
     * DetectLanguageResponse constructor.
     * @param int $code
     * @param string $lang
     */
    public function __construct(
        int $code,
        string $lang
    ) {
        $this->code = $code;
        $this->lang = $lang;
    }
    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }
    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }
}