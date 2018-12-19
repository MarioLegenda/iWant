<?php

namespace App\Ebay\Library\Component;

class ModifiedKeywordImplementation
{
    /**
     * @param string $keyword
     * @return string
     */
    public function makeExactSearch(string $keyword)
    {
        return sprintf('%s', implode(',', explode(' ', $keyword)));
    }
    /**
     * @param string $keyword
     * @param array $excluded
     * @return string
     */
    private function excludeKeywords(string $keyword, array $excluded): string
    {
        return sprintf('%s -(%s)', $keyword, implode(',', $excluded));
    }
}