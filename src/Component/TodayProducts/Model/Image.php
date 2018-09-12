<?php

namespace App\Component\TodayProducts\Model;

use App\Component\Selector\Ebay\Type\Nan;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Image implements ArrayNotationInterface
{
    /**
     * @var string $url
     */
    private $url;
    /**
     * @var string $width
     */
    private $width;
    /**
     * @var string $height
     */
    private $height;
    /**
     * Image constructor.
     * @param string $url
     * @param string $width
     * @param string $height
     */
    public function __construct(
        $url,
        string $width = null,
        string $height = null
    ) {
        $this->url = $url;
        $this->width = $width;
        $this->height = $height;
    }
    /**
     * @return string
     */
    public function getUrl(): string
    {
        if ($this->url instanceof Nan) {
            return (string) $this->url;
        }

        return $this->url;
    }
    /**
     * @return string
     */
    public function getWidth(): ?string
    {
        return $this->width;
    }
    /**
     * @return string
     */
    public function getHeight(): ?string
    {
        return $this->height;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'url' => $this->getUrl(),
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
        ];
    }
}