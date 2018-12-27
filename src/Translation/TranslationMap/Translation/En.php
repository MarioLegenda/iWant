<?php

namespace App\Translation\TranslationMap\Translation;

class En extends BaseLanguage
{
    /**
     * @var string $identifier
     */
    protected $identifier = 'en';
    /**
     * @var array $translationMap
     */
    private $translationMap = [
        'yes' => 'Yes',
        'no' => 'No',
        'languageChoiceExplanation' => 'All products searched will be translated to the language you select',
        'searchInputPlaceholder' => 'what would you like?',
        'searchingEbaySites' => 'Searching eBay sites',

    ];

    public function __construct()
    {
        $this->validateTranslationMap($this->translationMap);
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->identifier;
    }
}