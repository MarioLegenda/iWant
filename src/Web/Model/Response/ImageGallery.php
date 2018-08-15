<?php

namespace App\Web\Model\Response;

use App\Library\Infrastructure\Type\TypeInterface;
use App\Web\Model\Response\Type\DeferrableType;

class ImageGallery implements DeferrableHttpDataObjectInterface
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
    /**
     * @return iterable
     */
    public function getDeferrableData(): iterable
    {
        $message = sprintf(
            '%s already has all necessary data and does not have to be deferred therefor, %s::getDeferrableData() cannot be used',
            get_class($this),
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @return TypeInterface
     */
    public function getDeferrableType(): TypeInterface
    {
        return DeferrableType::fromValue('concrete_object');
    }
}