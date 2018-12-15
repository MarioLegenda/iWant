<?php

namespace App\Tests\Web\Unit;

use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Tests\Library\BasicSetup;
use App\Web\Factory\FindingApi\FindingApiResponseModelFactory;
use App\Web\Model\Response\ImageGallery;
use App\Web\Model\Response\SellerInfo;
use App\Web\Model\Response\ShippingInfo;
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
}