<?php

namespace App\Yandex\Library\Model;

use App\Library\Util\Util;

class SupportedLanguagesResponse implements ResponseModelInterface
{
    /**
     * @var array $languages
     */
    private $languages;
    /**
     * @var Language[] $languageObjects
     */
    private $languageObjects = [];
    /**
     * SupportedLanguagesResponse constructor.
     * @param array $languages
     */
    public function __construct(
        array $languages
    ) {
        $this->languages = $languages;
    }
    /**
     * @return array
     */
    public function getAllLanguages(): array
    {
        return $this->languages;
    }
    /**
     * @param string $code
     * @return bool
     */
    public function isSupported(string $code): bool
    {
        return array_key_exists($code, $this->languages);
    }
    /**
     * @param string $code
     * @return Language
     */
    public function getLanguage(string $code): Language
    {
        if (!$this->isSupported($code)) {
            $message = sprintf(
                'Code %s is not supported',
                $code
            );

            throw new \RuntimeException($message);
        }

        $langGen = Util::createGenerator($this->languages);

        foreach ($langGen as $entry) {
            $item = $entry['item'];
            $key = $entry['key'];

            if ($key === $code) {
                if (!array_key_exists($code, $this->languageObjects)) {
                    $this->languageObjects[$code] = $this->createLanguage([$key => $item]);
                }

                return $this->languageObjects[$code];
            }
        }
    }
    /**
     * @param array $lang
     * @return Language
     */
    private function createLanguage(array $lang): Language
    {
        $code = array_keys($lang)[0];
        $name = $lang[$code];

        return new Language($code, $name);
    }
}