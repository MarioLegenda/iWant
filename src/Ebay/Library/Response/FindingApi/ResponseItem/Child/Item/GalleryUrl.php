<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\AbstractItem;

class GalleryUrl extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var string $size
     */
    private $size;
    /**
     * @var string $url
     */
    private $url;
    /**
     * @param null $default
     * @return null
     */
    public function getSize($default = null)
    {
        if ($this->size === null) {
            $this->setSize((string) $this->simpleXml['gallerySize']);
        }

        if ($this->size === null and $default !== null) {
            return $default;
        }

        return $this->size;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getUrl($default = null)
    {
        if ($this->url === null) {
            $this->setUrl((string) $this->simpleXml);
        }

        if ($this->url === null and $default !== null) {
            return $default;
        }

        return $this->url;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'size' => $this->getSize(),
            'url' => $this->getUrl(),
        );
    }

    private function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    private function setSize(string $size) : GalleryUrl
    {
        $this->size = $size;

        return $this;
    }
}