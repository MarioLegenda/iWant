<?php

namespace App\Web\Model\Response;

use App\Library\Infrastructure\Type\TypeInterface;

class UniformedResponseModel
{
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * @var TypeInterface $shopType
     */
    private $shopType;
    /**
     * @var string $title
     */
    private $title;
    /**
     * @var string $description
     */
    private $description;
    /**
     * @var float $price
     */
    private $price;
    /**
     * @var ShippingInfo $shippingInfo
     */
    private $shippingInfo;
    /**
     * @var SellerInfo $sellerInfo
     */
    private $sellerInfo;
    /**
     * @var ImageGallery $imageGallery
     */
    private $imageGallery;
    /**
     * @var string $viewItemUrl
     */
    private $viewItemUrl;
    /**
     * @var bool $availableInYourCountry
     */
    private $availableInYourCountry;

    private $apiSpecificMetadata;
    /**
     * UniformedResponseModel constructor.
     * @param TypeInterface $shopType
     * @param string $itemId
     * @param string $title
     * @param string $description
     * @param float $price
     * @param ShippingInfo $shippingInfo
     * @param SellerInfo $sellerInfo
     * @param ImageGallery $imageGallery
     * @param string $viewItemUrl
     * @param bool $availableInYourCountry
     */
    public function __construct(
        TypeInterface $shopType,
        string $itemId,
        string $title,
        string $description,
        float $price,
        ShippingInfo $shippingInfo,
        SellerInfo $sellerInfo,
        ImageGallery $imageGallery,
        string $viewItemUrl,
        bool $availableInYourCountry
    ) {
        $this->itemId = $itemId;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->shippingInfo = $shippingInfo;
        $this->sellerInfo = $sellerInfo;
        $this->imageGallery = $imageGallery;
        $this->viewItemUrl = $viewItemUrl;
        $this->availableInYourCountry = $availableInYourCountry;
    }
}