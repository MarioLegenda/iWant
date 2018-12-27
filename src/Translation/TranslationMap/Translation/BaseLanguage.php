<?php

namespace App\Translation\TranslationMap\Translation;

use Symfony\Component\Yaml\Yaml;

abstract class BaseLanguage
{
    /**
     * @var array $cachedValidationTranslationMap
     */
    private $cachedValidationTranslationMap = null;
    /**
     * @param array $extendedTranslationMap
     */
    protected function validateTranslationMap(array $extendedTranslationMap)
    {
        if (!is_array($this->cachedValidationTranslationMap)) {
            $parsed = Yaml::parse(__DIR__.'/required_keys.yaml');

            $keys = $parsed['keys'];

            $this->cachedValidationTranslationMap = $keys;
        }

        $diff = array_diff(
            array_keys($extendedTranslationMap),
            $this->cachedValidationTranslationMap
        );

        if (!empty($diff)) {
            $message = sprintf(
                'Invalid translation map for locale %s. These values are not present in this locale: %s',
                (string) $this,
                implode(', ', $diff)
            );

            throw new \RuntimeException($message);
        }
    }
}