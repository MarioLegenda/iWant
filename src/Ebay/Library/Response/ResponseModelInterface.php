<?php

namespace App\Ebay\Library\Response;

interface ResponseModelInterface
{
    /**
     * @return RootItemInterface
     */
    public function getRoot() : RootItemInterface;
    /**
     * @return bool
     */
    public function isErrorResponse() : bool;
    /**
     * @return string
     */
    public function getRawResponse() : string;
}