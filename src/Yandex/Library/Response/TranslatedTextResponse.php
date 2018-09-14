<?php

namespace App\Yandex\Library\Response;

class TranslatedTextResponse implements ResponseModelInterface
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
     * TranslatedTextResponse constructor.
     * @param int $statusCode
     * @param string $lang
     */
    public function __construct(
        int $statusCode,
        string $lang
    ) {
        $this->statusCode = $statusCode;
        $this->lang = $lang;
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


}