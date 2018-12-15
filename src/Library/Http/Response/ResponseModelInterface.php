<?php

namespace App\Library\Http\Response;

use App\Library\Http\Request;

interface ResponseModelInterface
{
    /**
     * @return string
     */
    public function getBody(): string;
    /**
     * @return int
     */
    public function getStatusCode(): int;
    /**
     * @return string
     */
    public function getApiIdentifier(): string;
    /**
     * @return Request
     */
    public function getRequest(): Request;
    /**
     * @return bool
     */
    public function is400Range(): bool;
    /**
     * @return bool
     */
    public function is500Range(): bool;
    /**
     * @return bool
     */
    public function is200Range(): bool;
    /**
     * @return array
     */
    public function getBodyArrayIfJson(): ?array;
}