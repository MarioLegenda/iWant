<?php

namespace App\Yandex\Library\Model;

class TranslatedTextResponse
{
    /**
     * @var int $statusCode
     */
    private $statusCode;
    /**
     * @var string $lang
     */
    private $lang;
    /**
     * @var string $text
     */
    private $text;
    /**
     * TranslatedTextResponse constructor.
     * @param int $statusCode
     * @param string $lang
     * @param array $text
     */
    public function __construct(
        int $statusCode,
        string $lang,
        array $text
    ) {
        $this->statusCode = $statusCode;
        $this->lang = $lang;
        $this->text = rtrim(implode(' ', $text), ' ');
    }
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }
    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }


}