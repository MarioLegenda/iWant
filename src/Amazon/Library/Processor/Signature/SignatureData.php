<?php

namespace App\Amazon\Library\Processor\Signature;

class SignatureData
{
    /**
     * @var array $data
     */
    private $data = [];
    /**
     * SignatureData constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    /**
     * @param string $key
     * @param $data
     * @param bool $overwrite
     */
    public function add(string $key, $data, bool $overwrite = false)
    {
        if (!$this->has($key)) {
            $this->data[$key] = $data;
        }

        if ($this->has($key)) {
            if ($overwrite === true) {
                $this->data[$key] = $data;
            }
        }
    }
    /**
     * @param string $key
     * @return null|mixed
     */
    public function get(string $key)
    {
        if (!$this->has($key)) {
            return null;
        }

        return $this->data[$key];
    }
    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }
}