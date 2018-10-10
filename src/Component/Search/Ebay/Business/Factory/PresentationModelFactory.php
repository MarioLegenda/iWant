<?php

namespace App\Component\Search\Ebay\Business\Factory;

use App\Component\Search\Ebay\Model\Response\Image;
use App\Component\Search\Ebay\Model\Response\Nan;
use App\Component\Search\Ebay\Model\Response\Price;
use App\Component\Search\Ebay\Model\Response\SearchResponseModel;
use App\Component\Search\Ebay\Model\Response\Title;
use App\Component\TodayProducts\Selector\Ebay\Selector\SearchProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Library\Infrastructure\Type\TypeInterface;
use App\Library\MarketplaceType;
use App\Library\Representation\LanguageTranslationsRepresentation;
use App\Yandex\Library\Request\RequestFactory;
use App\Yandex\Library\Response\DetectLanguageResponse;
use App\Yandex\Library\Response\TranslatedTextResponse;
use App\Yandex\Presentation\EntryPoint\YandexEntryPoint;

class PresentationModelFactory
{
    /**
     * @var YandexEntryPoint $yandexEntryPoint
     */
    private $yandexEntryPoint;
    /**
     * @var LanguageTranslationsRepresentation $languageTranslationRepresentation
     */
    private $languageTranslationRepresentation;
    /**
     * PresentationModelFactory constructor.
     * @param YandexEntryPoint $yandexEntryPoint
     * @param LanguageTranslationsRepresentation $languageTranslationsRepresentation
     */
    public function __construct(
        YandexEntryPoint $yandexEntryPoint,
        LanguageTranslationsRepresentation $languageTranslationsRepresentation
    ) {
        $this->yandexEntryPoint = $yandexEntryPoint;
        $this->languageTranslationRepresentation = $languageTranslationsRepresentation;
    }
    /**
     * @param Item $singleItem
     * @param ApplicationShop $applicationShop
     * @param array $shippingLocations
     * @return SearchResponseModel
     */
    public function createModel(
        Item $singleItem,
        ApplicationShop $applicationShop,
        array $shippingLocations
    ): SearchResponseModel {
        $itemId = (string) $singleItem->getItemId();
        $title = new Title($singleItem->getTitle());
        $shopName = $singleItem->getStoreInfo()->getStoreName();
        $price = $this->createPrice($singleItem->getSellingStatus()->getCurrentPrice());
        $viewItemUrl = $singleItem->getViewItemUrl();
        $image = $this->createImage($singleItem);
        $globalId = $singleItem->getGlobalId();
        $marketplace = MarketplaceType::fromValue('Ebay');
        $staticUrl = $this->createStaticUrl(
            $marketplace,
            $title->getOriginal(),
            (string) $itemId
        );

        $taxonomyName = $applicationShop->getNativeTaxonomy()->getName();

        $model = new SearchResponseModel(
            $itemId,
            $title,
            $image,
            $shopName,
            $price,
            $viewItemUrl,
            $marketplace,
            $staticUrl,
            $taxonomyName,
            $shippingLocations,
            $globalId
        );

        $this->translateModelIfNecessary($model);

        return $model;
    }
    /**
     * @param array $priceInfo
     * @return Price
     */
    private function createPrice(array $priceInfo): Price
    {
        return new Price(
            $priceInfo['currencyId'],
            $priceInfo['currentPrice']
        );
    }
    /**
     * @param MarketplaceType|TypeInterface $marketplace
     * @param string $title
     * @param string $itemId
     * @return string
     */
    private function createStaticUrl(
        MarketplaceType $marketplace,
        string $title,
        string $itemId
    ): string {
        return sprintf(
            '/item/%s/%s/%s',
            (string) $marketplace,
            \URLify::filter($title),
            $itemId
        );
    }
    /**
     * @param Item $model
     * @return Image
     */
    private function createImage(Item $model): Image
    {
        $url = $model->getGalleryUrl();

        if (is_string($url)) {
            $imageSize = getimagesize($url);

            $width = $imageSize[0];
            $height = $imageSize[1];

            return new Image($url, $width, $height);
        }

        return new Image(
            Nan::fromValue()
        );
    }
    /**
     * @param SearchResponseModel $model
     */
    private function translateModelIfNecessary(SearchResponseModel $model)
    {
        if (GlobalIdInformation::instance()->has($model->getGlobalId())) {
            if ($this->languageTranslationRepresentation->isMappedByGlobalId($model->getGlobalId())) {
                /** @var Title $title */
                $title = $model->getTitle();

                $detectLanguageModel = RequestFactory::createDetectLanguageRequestModel($title->getOriginal());

                /** @var DetectLanguageResponse $detectedResponse */
                $detectedResponse = $this->yandexEntryPoint->detectLanguage($detectLanguageModel);

                $translationModel = RequestFactory::createTranslateRequestModel(
                    $title->getOriginal(),
                    $detectedResponse->getLang()
                );

                /** @var TranslatedTextResponse $translated */
                $translated = $this->yandexEntryPoint->translate($translationModel);

                $model->setTitle($translated->getText());
                $model->translated(true);
            }
        }
    }
}