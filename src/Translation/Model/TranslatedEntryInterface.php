<?php

namespace App\Translation\Model;

interface TranslatedEntryInterface
{
    /**
     * @return string
     */
    public function getEntry(): string;
}