<?php

namespace App\Tests\Web\Unit;

use App\Library\Infrastructure\Helper\TypedArray;
use App\Tests\Library\BasicSetup;
use App\Web\Factory\FindingApi\FindingApiResponseModelFactory;
use App\Tests\Ebay\FindingApi\DataProvider\DataProvider as FindingApiDataProvider;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Web\Library\Grouping\GroupContract\PriceGroupingInterface;
use App\Web\Library\Grouping\Grouping;
use App\Web\Library\Grouping\Type\HighestPriceGroupingType;
use App\Web\Library\Grouping\Type\LowestPriceGroupingType;
use App\Web\Model\Response\UniformedResponseModel;

class GroupingTest extends BasicSetup
{
    public function test_lowest_price()
    {
        /** @var FindingApiResponseModelFactory $findingApiModelFactory */
        $findingApiModelFactory = $this->locator->get(FindingApiResponseModelFactory::class);
        /** @var FindingApiDataProvider $ebayModelProvider */
        $ebayDataProvider = $this->locator->get('data_provider.finding_api');
        /** @var FindingApiEntryPoint $findingApiEntryPoint */
        $findingApiEntryPoint = $this->locator->get(FindingApiEntryPoint::class);

        /** @var FindingApiResponseModelInterface $findingApiResponseModel */
        $findingApiResponseModel = $findingApiRequestModel = $findingApiEntryPoint->findItemsByKeywords(
            $ebayDataProvider->getFindItemsByKeywordsData([
                'boots', 'mountain',
            ])
        );

        static::assertInstanceOf(FindingApiResponseModelInterface::class, $findingApiResponseModel);

        /** @var TypedArray|UniformedResponseModel[]|PriceGroupingInterface[] $presentationModels */
        $presentationModels = $findingApiModelFactory->createModels($findingApiResponseModel);

        $grouped = Grouping::inst()->groupBy(
            LowestPriceGroupingType::fromValue(),
            $presentationModels
        );

        $previous = $grouped[0]->getPrice();
        /** @var UniformedResponseModel $presentationModel */
        foreach ($grouped as $numericKey => $presentationModel) {
            if ($numericKey === 0) {
                continue;
            }

            $this->assertLowerThanOrEquals($presentationModel->getPrice(), $previous);

            $previous = $presentationModel->getPrice();
        }
    }

    public function test_highest_price()
    {
        /** @var FindingApiResponseModelFactory $findingApiModelFactory */
        $findingApiModelFactory = $this->locator->get(FindingApiResponseModelFactory::class);
        /** @var FindingApiDataProvider $ebayModelProvider */
        $ebayDataProvider = $this->locator->get('data_provider.finding_api');
        /** @var FindingApiEntryPoint $findingApiEntryPoint */
        $findingApiEntryPoint = $this->locator->get(FindingApiEntryPoint::class);

        /** @var FindingApiResponseModelInterface $findingApiResponseModel */
        $findingApiResponseModel = $findingApiRequestModel = $findingApiEntryPoint->findItemsByKeywords(
            $ebayDataProvider->getFindItemsByKeywordsData([
                'boots', 'mountain',
            ])
        );

        static::assertInstanceOf(FindingApiResponseModelInterface::class, $findingApiResponseModel);

        /** @var TypedArray|UniformedResponseModel[]|PriceGroupingInterface[] $presentationModels */
        $presentationModels = $findingApiModelFactory->createModels($findingApiResponseModel);

        $grouped = Grouping::inst()->groupBy(
            HighestPriceGroupingType::fromValue(),
            $presentationModels
        );

        $previous = $grouped[0]->getPrice();
        /** @var UniformedResponseModel $presentationModel */
        foreach ($grouped as $numericKey => $presentationModel) {
            if ($numericKey === 0) {
                continue;
            }

            $this->assertGreaterThanOrEquals($presentationModel->getPrice(), $previous);

            $previous = $presentationModel->getPrice();
        }
    }
}