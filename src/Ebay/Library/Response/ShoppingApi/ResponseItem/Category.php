<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

class Category extends AbstractItem
{
    /**
     * @var string $categoryId
     */
    private $categoryId;
    /**
     * @var string $categoryIdPath
     */
    private $categoryIdPath;
    /**
     * @var int $categoryLevel
     */
    private $categoryLevel;
    /**
     * @var string $categoryName
     */
    private $categoryName;
    /**
     * @var string $categoryNamePath
     */
    private $categoryNamePath;
    /**
     * @var string $categoryParentId
     */
    private $categoryParentId;
    /**
     * @var bool $leafCategory
     */
    private $leafCategory;
    /**
     * @param null $default
     * @return string
     */
    public function getCategoryId($default = null): string
    {
        if ($this->categoryId === null) {
            if (!empty($this->simpleXml->CategoryID)) {
                $this->setCategoryId((string) $this->simpleXml->CategoryID);
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
    public function getCategoryIdPath($default = null): string
    {
        if ($this->categoryIdPath === null) {
            if (!empty($this->simpleXml->CategoryIDPath)) {
                $this->setCategoryIdPath((string) $this->simpleXml->CategoryIDPath);
            }
        }

        if ($this->categoryIdPath === null and $default !== null) {
            return $default;
        }

        return $this->categoryIdPath;
    }
    /**
     * @param null $default
     * @return int
     */
    public function getCategoryLevel($default = null): int
    {
        if ($this->categoryLevel === null) {
            if (!empty($this->simpleXml->CategoryLevel)) {
                $this->setCategoryLevel((int) $this->simpleXml->CategoryLevel);
            }
        }

        if ($this->categoryLevel === null and $default !== null) {
            return $default;
        }

        return $this->categoryLevel;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getCategoryName($default = null): string
    {
        if ($this->categoryName === null) {
            if (!empty($this->simpleXml->CategoryName)) {
                $this->setCategoryName((string) $this->simpleXml->CategoryName);
            }
        }

        if ($this->categoryName === null and $default !== null) {
            return $default;
        }

        return $this->categoryName;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getCategoryNamePath($default = null): string
    {
        if ($this->categoryNamePath === null) {
            if (!empty($this->simpleXml->CategoryNamePath)) {
                $this->setCategoryNamePath((string) $this->simpleXml->CategoryNamePath);
            }
        }

        if ($this->categoryNamePath === null and $default !== null) {
            return $default;
        }

        return $this->categoryNamePath;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getCategoryParentId($default = null): string
    {
        if ($this->categoryParentId === null) {
            if (!empty($this->simpleXml->CategoryParentID)) {
                $this->setCategoryParentId((string) $this->simpleXml->CategoryParentID);
            }
        }

        if ($this->categoryIdPath === null and $default !== null) {
            return $default;
        }

        return $this->categoryIdPath;
    }
    /**
     * @param null $default
     * @return bool
     */
    public function getLeafCategory($default = null): bool
    {
        if ($this->leafCategory=== null) {
            if (!empty($this->simpleXml->LeafCategory)) {
                $this->setLeafCategory((bool) $this->convertStringToBool($this->simpleXml->LeafCategory));
            }
        }

        if ($this->leafCategory === null and $default !== null) {
            return $default;
        }

        return $this->leafCategory;
    }
    /**
     * @param bool $value
     */
    private function setLeafCategory(bool $value)
    {
        $this->leafCategory = $value;
    }
    /**
     * @param string $value
     */
    private function setCategoryParentId(string $value)
    {
        $this->categoryParentId = $value;
    }
    /**
     * @param string $value
     */
    private function setCategoryNamePath(string $value)
    {
        $this->categoryNamePath = $value;
    }
    /**
     * @param string $value
     */
    private function setCategoryName(string $value)
    {
        $this->categoryName = $value;
    }
    /**
     * @param string $value
     */
    private function setCategoryId(string $value)
    {
        $this->categoryId = $value;
    }
    /**
     * @param string $value
     */
    private function setCategoryIdPath(string $value)
    {
        $this->categoryIdPath = $value;
    }
    /**
     * @param int $value
     */
    private function setCategoryLevel(int $value)
    {
        $this->categoryLevel = $value;
    }
    /**
     * @param string $boolValue
     * @return bool
     */
    private function convertStringToBool(string $boolValue)
    {
        return $boolValue === 'true';
    }
}