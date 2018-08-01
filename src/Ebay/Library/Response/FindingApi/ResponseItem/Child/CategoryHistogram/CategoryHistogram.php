<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\CategoryHistogram;

use FindingAPI\Core\Response\ArrayConvertableInterface;
use FindingAPI\Core\ResponseParser\ResponseItem\AbstractItemIterator;

class CategoryHistogram extends AbstractItemIterator implements ArrayConvertableInterface
{
    /**
     * @var int $depth
     */
    private $depth;
    /**
     * @var CategoryHistogram $parent
     */
    private $parent;
    /**
     * @var string $categoryId
     */
    private $categoryId;
    /**
     * @var string $categoryName
     */
    private $categoryName;
    /**
     * @var int $count
     */
    private $count;
    /**
     * CategoryHistogram constructor.
     * @param \SimpleXMLElement $simpleXML
     * @param CategoryHistogram $parent
     * @param int $depth
     */
    public function __construct(\SimpleXMLElement $simpleXML, CategoryHistogram $parent = null, int $depth = 0)
    {
        parent::__construct($simpleXML);

        $this->parent = $parent;

        $this->depth = $depth;

        if (!empty($simpleXML->childCategoryHistogram)) {
            $this->loadChildCategoryHistograms($simpleXML->childCategoryHistogram);
        }
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getCategoryId($default = null)
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
     * @return null|string
     */
    public function getCategoryName($default = null)
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
     * @param null $default
     * @return int|null
     */
    public function getCount($default = null)
    {
        if ($this->count === null) {
            if (!empty($this->simpleXml->count)) {
                $this->setCount((int) $this->simpleXml->count);
            }
        }

        if ($this->count === null and $default !== null) {
            return $default;
        }

        return $this->count;
    }
    /**
     * @return CategoryHistogram
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        $toArray['categoryId'] = $this->getCategoryId();
        $toArray['categoryName'] = $this->getCategoryName();
        $toArray['count'] = $this->getCount();
        $toArray['depth'] = $this->getDepth();

        $toArray['children'] = array();

        if (!$this->isEmpty()) {
            foreach ($this->items as $item) {
                $toArray['children'][] = $item->toArray();
            }
        }

        return $toArray;
    }

    private function setCount(int $count) : CategoryHistogram
    {
        $this->count = $count;

        return $this;
    }

    private function setCategoryName(string $categoryName) : CategoryHistogram
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    private function setCategoryId(string $categoryId) : CategoryHistogram
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    private function loadChildCategoryHistograms(\SimpleXMLElement $simpleXml)
    {
        $depth = $this->depth + 1;
        foreach ($simpleXml as $childCategoryHistogram) {
            $this->addItem(new CategoryHistogram($childCategoryHistogram, $this, $depth));
        }
    }
}