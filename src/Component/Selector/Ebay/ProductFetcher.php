<?php

namespace App\Component\Selector\Ebay;

use App\Component\Selector\Ebay\Factory\ProductModelFactory;
use App\Component\Selector\Ebay\Selector\SearchProduct;
use App\Component\Selector\Ebay\Selector\SelectorFive;
use App\Component\Selector\Ebay\Selector\SelectorFour;
use App\Component\Selector\Ebay\Selector\SelectorOne;
use App\Component\Selector\Ebay\Selector\SelectorSix;
use App\Component\Selector\Ebay\Selector\SelectorThree;
use App\Component\Selector\Ebay\Selector\SelectorTwo;
use App\Component\TodayProducts\Model\Title;
use App\Component\TodayProducts\Model\TodayProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Representation\LanguageTranslationsRepresentation;
use App\Yandex\Library\Request\CallType;
use App\Yandex\Library\Request\RequestFactory;
use App\Yandex\Library\Response\TranslatedTextResponse;
use App\Yandex\Presentation\EntryPoint\YandexEntryPoint;
use App\Yandex\Presentation\Model\Query;
use App\Yandex\Presentation\Model\YandexRequestModel;
use App\Yandex\Presentation\Model\YandexRequestModelInterface;
use BlueDot\BlueDot;

class ProductFetcher
{
    /**
     * @var BlueDot $blueDot
     */
    private $blueDot;
    /**
     * @var ApplicationShopRepository $applicationShopRepository
     */
    private $applicationShopRepository;
    /**
     * @var ProductSelector $productSelector
     */
    private $productSelector;
    /**
     * @var ProductModelFactory $productModelFactory
     */
    private $productModelFactory;
    /**
     * @var YandexEntryPoint $yandexEntryPoint
     */
    private $yandexEntryPoint;
    /**
     * @var LanguageTranslationsRepresentation $languageTranslationRepresentation
     */
    private $languageTranslationRepresentation;
    /**
     * ProductFetcher constructor.
     * @param BlueDot $blueDot
     * @param ApplicationShopRepository $applicationShopRepository
     * @param ProductSelector $productSelector
     * @param ProductModelFactory $productModelFactory
     * @param YandexEntryPoint $yandexEntryPoint
     * @param LanguageTranslationsRepresentation $languageTranslationsRepresentation
     */
    public function __construct(
        BlueDot $blueDot,
        ApplicationShopRepository $applicationShopRepository,
        ProductSelector $productSelector,
        ProductModelFactory $productModelFactory,
        YandexEntryPoint $yandexEntryPoint,
        LanguageTranslationsRepresentation $languageTranslationsRepresentation
    ) {
        $this->blueDot = $blueDot;
        $this->applicationShopRepository = $applicationShopRepository;
        $this->productSelector = $productSelector;
        $this->productModelFactory = $productModelFactory;
        $this->yandexEntryPoint = $yandexEntryPoint;
        $this->languageTranslationRepresentation = $languageTranslationsRepresentation;
    }
    /**
     * @return iterable
     * @throws \App\Symfony\Exception\HttpException
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getProducts(): iterable
    {
        $responseModels = $this->getResponseModels();

        return $this->createTodaysProductModels($responseModels);
    }
    /**
     * @param TypedArray $searchProducts
     * @return TypedArray
     */
    private function createTodaysProductModels(TypedArray $searchProducts): TypedArray
    {
        $todayProductModels = TypedArray::create('integer', TodayProduct::class);

        /** @var SearchProduct $searchProduct */
        foreach ($searchProducts as $searchProduct) {
            /** @var Item $singleItem */
            $singleItem = $searchProduct->getResponseModels()->getSearchResults()[0];
            /** @var TodayProduct $productModel */
            $productModel = $this->productModelFactory->createModel(
                $singleItem,
                $searchProduct->getApplicationShop(),
                $searchProduct->getShippingInformation()
            );

            $this->translateProductIfNecessary($productModel);

            $todayProductModels[] = $productModel;
        }

        return $todayProductModels;
    }
    /**
     * @return TypedArray
     * @throws \App\Symfony\Exception\HttpException
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getResponseModels(): TypedArray
    {
        $this->blueDot->useRepository('util');

        $promise = $this->blueDot->execute('simple.select.get_application_shop_ids_by_marketplace', [
            'marketplace' => (string) MarketplaceType::fromValue('Ebay'),
        ]);

        $applicationShopIds = $promise->getResult()['data']['id'];

        $shopSuccessCount = 0;
        $visitedShops = [];
        for (;;) {
            $index = array_rand($applicationShopIds, 1);

            if (in_array($index, $visitedShops) === true) {
                continue;
            }

            $visitedShops[] = $index;

            /** @var ApplicationShop $applicationShop */
            $applicationShop = $this->applicationShopRepository->find(
                $applicationShopIds[$index]
            );

            $this->productSelector
                ->attach(new SelectorOne($applicationShop))
                ->attach(new SelectorTwo($applicationShop))
                ->attach(new SelectorThree($applicationShop))
                ->attach(new SelectorFour($applicationShop));

            try {
                $this->productSelector->notify($applicationShop);

                $shopSuccessCount++;
            } catch (\Exception $e) {}

            if ($shopSuccessCount === 4) {
                break;
            }
        }

        return $this->productSelector->getProductResponseModels();
    }
    /**
     * @param TodayProduct $product
     */
    private function translateProductIfNecessary(TodayProduct $product)
    {
        if (GlobalIdInformation::instance()->has($product->getGlobalId())) {
            if ($this->languageTranslationRepresentation->isMappedByGlobalId($product->getGlobalId())) {
                /** @var Title $title */
                $title = $product->getTitle();

                $model = RequestFactory::createTranslateRequestModel(
                    $title->getOriginal(),
                    'en'
                );

                /** @var TranslatedTextResponse $translated */
                $translated = $this->yandexEntryPoint->translate($model);

                $product->setTitle($translated->getText());
            }
        }
    }
}