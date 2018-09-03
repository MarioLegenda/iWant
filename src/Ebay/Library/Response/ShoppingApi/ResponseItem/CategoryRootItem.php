<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

class CategoryRootItem extends RootItem
{
    /**
     * @var int $categoryCount
     */
    private $categoryCount;
    /**
     * @var string $categoryVersion
     */
    private $categoryVersion;
    /**
     * @return int
     */
    public function getCategoryCount(): int
    {
        if ($this->categoryCount === null) {
            $this->setCategoryCount((int) $this->simpleXml->CategoryCount);
        }

        return $this->categoryCount;
    }
    /**
     * @return string
     */
    public function getCategoryVersion(): string
    {
        if ($this->categoryVersion === null) {
            $this->setCategoryVersion((int) $this->simpleXml->CategoryVersion);
        }

        return $this->categoryVersion;
    }
    /**
     * @param int $value
     */
    private function setCategoryCount(int $value)
    {
        $this->categoryCount = $value;
    }
    /**
     * @param string $value
     */
    private function setCategoryVersion(string $value)
    {
        $this->categoryVersion = $value;
    }
}