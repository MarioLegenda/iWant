<?php

namespace App\Web\Model\Response;

class UniformedResponseModel
{
    /**
     * @var string $itemId
     */
    private $itemId;
    /**
     * @var string $title
     */
    private $title;
    /**
     * @var string $description
     */
    private $description;
    /**
     * @var string $price
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

    private $apiSpecificMetadata;
}