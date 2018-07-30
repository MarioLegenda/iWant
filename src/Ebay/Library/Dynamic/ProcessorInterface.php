<?php

namespace App\Ebay\Library\Dynamic;

interface ProcessorInterface
{
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface;
    /**
     * @return string
     */
    public function getProcessed(): string;
}