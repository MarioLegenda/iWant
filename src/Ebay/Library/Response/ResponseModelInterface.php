<?php

namespace App\Ebay\Library\Response;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

interface ResponseModelInterface
{
    /**
     * @return RootItemInterface
     */
    public function getRoot();
    /**
     * @return ArrayNotationInterface
     */
    public function getErrors();
}