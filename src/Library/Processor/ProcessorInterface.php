<?php

namespace App\Library\Processor;

use App\Library\Tools\LockedImmutableHashSet;

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
     * @return mixed
     */
    public function getProcessed();
    /**
     * @return string
     */
    public function getDelimiter(): string;
}