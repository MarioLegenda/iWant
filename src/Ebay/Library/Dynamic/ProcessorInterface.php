<?php

namespace App\Ebay\Library\Dynamic;

use App\Ebay\Library\Tools\LockedImmutableHashSet;

interface ProcessorInterface
{
    /**
     * @return ProcessorInterface
     */
    public function process(): ProcessorInterface;
    /**
     * @param LockedImmutableHashSet $options
     * @return ProcessorInterface
     */
    public function setOptions(LockedImmutableHashSet $options): ProcessorInterface;
    /**
     * @return string
     */
    public function getProcessed(): string;
}