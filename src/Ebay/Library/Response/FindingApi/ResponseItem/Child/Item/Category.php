<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\AbstractItem;

class Category extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var string $categoryId
     */
    private $categoryId;
    /**
     * @var string $categoryName
     */
    private $categoryName;
    /**
     * @param null $default
     * @return string
     */
    public function getCategoryId($default = null) : string
    {
        if ($this->categoryId === null) {
            if (!empty($this->simpleXml->categoryId)) {
                $this->setCategoryId((string) $this->simpleXml->categoryId);
            }
        }

        if ($this->categoryId === null and $default !== null) {
            return $default;
        }

        return $this->categoryId;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getCategoryName($default = null) : string
    {
        if ($this->categoryName === null) {
            if (!empty($this->simpleXml->categoryName)) {
                $this->setCategoryName((string) $this->simpleXml->categoryName);
            }
        }

        if ($this->categoryName === null and $default !== null) {
            return $default;
        }

        return $this->categoryName;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'categoryId' => $this->getCategoryId(),
            'categoryName' => $this->getCategoryName(),
        );
    }
    /**
     * @param string $categoryId
     * @return Category
     */
    private function setCategoryId(string $categoryId) : Category
    {
        $this->categoryId = $categoryId;

        return $this;
    }
    /**
     * @param string $categoryName
     * @return Category
     */
    public function setCategoryName(string $categoryName) : Category
    {
        $this->categoryName = $categoryName;

        return $this;
    }
}