<?php

namespace App\Web\Model\Response;

class ImageGallery
{
    /**
     * @var string $mainGalleryUrl
     */
    private $mainGalleryUrl;
    /**
     * ImageGallery constructor.
     * @param string $mainGalleryUrl
     */
    public function __construct(
        string $mainGalleryUrl
    ) {
        $this->mainGalleryUrl = $mainGalleryUrl;
    }
    /**
     * @return string
     */
    public function getMainGalleryUrl(): string
    {
        return $this->mainGalleryUrl;
    }
}