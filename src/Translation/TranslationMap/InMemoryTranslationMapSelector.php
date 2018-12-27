<?php

namespace App\Translation\TranslationMap;

use App\Library\Infrastructure\Helper\TypedArray;

class InMemoryTranslationMapSelector implements TranslationMapSelectorInterface
{
    /**
     * @var TypedArray $translationMap
     */
    private $translationMap = [];

    public function __construct()
    {

    }

    public function getByLocale(string $locale)
    {

    }
}