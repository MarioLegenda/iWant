<?php

namespace App\Tests\Web\Unit;

use App\Bonanza\Library\Response\BonanzaApiResponseModelInterface;
use App\Bonanza\Presentation\BonanzaApiEntryPoint;
use App\Bonanza\Presentation\Model\BonanzaApiModel;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Tests\Etsy\DataProvider\DataProvider as EtsyDataProvider;
use App\Tests\Bonanza\DataProvider\DataProvider as BonanzaDataProvider;
use App\Tests\Library\BasicSetup;
use App\Web\Factory\BonanzaResponseModelFactory;
use App\Web\Factory\EtsyResponseModelFactory;
use App\Web\Factory\FindingApi\FindingApiResponseModelFactory;
use App\Web\Model\Response\DeferrableHttpDataObject;
use App\Web\Model\Response\ImageGallery;
use App\Web\Model\Response\SellerInfo;
use App\Web\Model\Response\ShippingInfo;
use App\Web\Model\Response\Type\DeferrableType;
use App\Web\Model\Response\UniformedResponseModel;
use App\Tests\Ebay\FindingApi\DataProvider\DataProvider as FindingApiDataProvider;

class PresentationModelTest extends BasicSetup
{
    public function test_ebay_presentation_creation()
    {
        /** @var FindingApiResponseModelFactory $findingApiModelFactory */
        $findingApiModelFactory = $this->locator->get(FindingApiResponseModelFactory::class);
        /** @var FindingApiDataProvider $ebayModelProvider */
        $ebayDataProvider = $this->locator->get('data_provider.finding_api');
        /** @var FindingApiEntryPoint $findingApiEntryPoint */
        $findingApiEntryPoint = $this->locator->get(FindingApiEntryPoint::class);

        /** @var FindingApiResponseModelInterface $findingApiResponseModel */
        $findingApiResponseModel = $findingApiRequestModel = $findingApiEntryPoint->findItemsByKeywords(
            $ebayDataProvider->getFindItemsByKeywordsData('boots for mountain')
        );

        static::assertInstanceOf(FindingApiResponseModelInterface::class, $findingApiResponseModel);

        /** @var UniformedResponseModel[] $presentationModels */
        $presentationModels = $findingApiModelFactory->createModels($findingApiResponseModel);

        /** @var UniformedResponseModel $presentationModel */
        foreach ($presentationModels as $presentationModel) {
            static::assertInternalType('string', $presentationModel->getItemId());
            static::assertInternalType('float', $presentationModel->getPrice());
            static::assertInternalType('string', $presentationModel->getDescription());
            static::assertInternalType('string', $presentationModel->getViewItemUrl());
            static::assertInternalType('string', $presentationModel->getTitle());
            static::assertInstanceOf(ShippingInfo::class, $presentationModel->getShippingInfo());
            static::assertInstanceOf(SellerInfo::class, $presentationModel->getSellerInfo());
            static::assertInstanceOf(ImageGallery::class, $presentationModel->getImageGallery());
            static::assertInternalType('bool', $presentationModel->isAvailableInYourCountry());
        }
    }

    public function test_etsy_presentation_creation()
    {
        /** @var EtsyResponseModelFactory $etsyModelFactory */
        $etsyModelFactory = $this->locator->get(EtsyResponseModelFactory::class);
        /** @var EtsyDataProvider $etsyModelProvider */
        $etsyModelProvider = $this->locator->get('data_provider.etsy_api');
        /** @var EtsyApiEntryPoint $etsyEntryPoint */
        $etsyEntryPoint = $this->locator->get(EtsyApiEntryPoint::class);
        /** @var EtsyApiModel $etsyApiModel */
        $etsyApiModel = $etsyModelProvider->getEtsyApiModel();

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $etsyEntryPoint->findAllListingActive($etsyApiModel);

        static::assertInstanceOf(EtsyApiResponseModelInterface::class, $responseModel);

        /** @var UniformedResponseModel[] $presentationModels */
        $presentationModels = $etsyModelFactory->createModels($responseModel);

        /** @var UniformedResponseModel $presentationModel */
        foreach ($presentationModels as $presentationModel) {
            static::assertInternalType('string', $presentationModel->getItemId());
            static::assertInternalType('float', $presentationModel->getPrice());
            static::assertInternalType('string', $presentationModel->getDescription());
            static::assertInternalType('string', $presentationModel->getViewItemUrl());
            static::assertInternalType('string', $presentationModel->getTitle());
            static::assertInstanceOf(DeferrableHttpDataObject::class, $presentationModel->getShippingInfo());
            static::assertInstanceOf(DeferrableHttpDataObject::class, $presentationModel->getSellerInfo());
            static::assertInstanceOf(DeferrableHttpDataObject::class, $presentationModel->getImageGallery());
            static::assertInternalType('bool', $presentationModel->isAvailableInYourCountry());

            /** @var DeferrableHttpDataObject $shippingInfo */
            $shippingInfo = $presentationModel->getShippingInfo();

            static::assertInstanceOf(DeferrableHttpDataObject::class, $shippingInfo);
            static::assertInstanceOf(DeferrableType::class, $shippingInfo->getDeferrableType());

            static::assertEquals($shippingInfo->getDeferrableType()->getValue(), DeferrableType::fromValue('http_deferrable')->getValue());

            $deferrableData = $shippingInfo->getDeferrableData();

            static::assertInternalType('array', $deferrableData);
            static::assertNotEmpty($deferrableData);
            static::assertInternalType('string', $deferrableData['listingId']);
            static::assertNotEmpty($deferrableData['listingId']);

            /** @var DeferrableHttpDataObject $sellerInfo */
            $sellerInfo = $presentationModel->getSellerInfo();

            static::assertInstanceOf(DeferrableHttpDataObject::class, $sellerInfo);
            static::assertInstanceOf(DeferrableType::class, $sellerInfo->getDeferrableType());

            static::assertEquals($sellerInfo->getDeferrableType()->getValue(), DeferrableType::fromValue('http_deferrable')->getValue());

            $deferrableData = $sellerInfo->getDeferrableData();

            static::assertInternalType('array', $deferrableData);
            static::assertNotEmpty($deferrableData);
            static::assertInternalType('string', $deferrableData['userId']);
            static::assertNotEmpty($deferrableData['userId']);

            /** @var DeferrableHttpDataObject $imageGallery */
            $imageGallery = $presentationModel->getImageGallery();

            static::assertInstanceOf(DeferrableHttpDataObject::class, $imageGallery);
            static::assertInstanceOf(DeferrableType::class, $imageGallery->getDeferrableType());

            static::assertEquals($imageGallery->getDeferrableType()->getValue(), DeferrableType::fromValue('http_deferrable')->getValue());

            $deferrableData = $imageGallery->getDeferrableData();

            static::assertInternalType('array', $deferrableData);
            static::assertNotEmpty($deferrableData);
            static::assertInternalType('string', $deferrableData['listingId']);
            static::assertNotEmpty($deferrableData['listingId']);
        }
    }
}