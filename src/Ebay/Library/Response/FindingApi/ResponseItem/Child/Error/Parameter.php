<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Error;

use App\Ebay\Library\Response\ResponseItem\AbstractItem;

class Parameter extends AbstractItem
{
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $parameter
     */
    private $parameter;
    /**
     * @param null $default
     * @return null|string
     */
    public function getParameterName($default = null)
    {
        if ($this->name === null) {
            if (!empty($this->simpleXml['name'])) {
                $this->setName((string) $this->simpleXml['name']);
            }
        }

        if ($this->name === null and $default !== null) {
            return $default;
        }

        return $this->name;
    }

    /**
     * @param null $default
     * @return null|string
     */
    public function getParameter($default = null)
    {
        if ($this->parameter === null) {
            if (!empty($this->simpleXml)) {
                $this->setParameter((string) $this->simpleXml);
            }
        }

        if ($this->parameter === null and $default !== null) {
            return $default;
        }

        return $this->parameter;
    }

    private function setParameter(string $parameter) : Parameter
    {
        $this->parameter = $parameter;

        return $this;
    }

    private function setName(string $name) : Parameter
    {
        $this->name = $name;

        return $this;
    }
}