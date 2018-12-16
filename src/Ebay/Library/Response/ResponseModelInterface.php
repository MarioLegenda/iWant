<?php

namespace App\Ebay\Library\Response;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

interface ResponseModelInterface
{
    /**
     * @return RootItemInterface
     */
    public function getRoot() : RootItemInterface;
    /**
     * @return ArrayNotationInterface
     */
    public function getErrors();
    /**
     * @return bool
     */
    public function isErrorResponse() : bool;
    /**
     * @return string
     */
    public function getRawResponse() : string;
}