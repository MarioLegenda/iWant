<?php

namespace App\Etsy\Library\Dynamic;

class DynamicErrors
{
    /**
     * @var array|string[] $errors
     */
    private $errors = [];

    /**
     * @param string $error
     */
    public function add(string $error)
    {
        $this->errors[] = $error;
    }
    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->errors);
    }
}