<?php

namespace App\Component\Selector\Ebay;

use App\Component\Selector\Ebay\Selector\SearchProduct;
use App\Doctrine\Entity\ApplicationShop;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\RootItem;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Library\Infrastructure\Helper\TypedArray;
use Twig\Node\Expression\Binary\SubBinary;

class ProductSelector implements SubjectSelectorInterface
{
    /**
     * @var \SplObserver[] $observers
     */
    private $observers;
    /**
     * @var SearchProduct[] $searchProducts
     */
    private $searchProducts;
    /**
     * @var FindingApiEntryPoint $findingApiEntryPoint
     */
    private $findingApiEntryPoint;
    /**
     * ProductSelector constructor.
     * @param FindingApiEntryPoint $findingApiEntryPoint
     */
    public function __construct(
        FindingApiEntryPoint $findingApiEntryPoint
    ) {
        $this->findingApiEntryPoint = $findingApiEntryPoint;
        $this->searchProducts = TypedArray::create('integer', SearchProduct::class);
    }
    /**
     * @param ObserverSelectorInterface $observer
     */
    public function detach(ObserverSelectorInterface $observer)
    {
        $message = sprintf(
            'Method %s::detach() is disabled',
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param \SplObserver $observer
     * @return SubjectSelectorInterface
     */
    public function attach(ObserverSelectorInterface $observer): SubjectSelectorInterface
    {
        $this->observers[] = $observer;

        return $this;
    }
    /**
     * @param ApplicationShop $applicationShop
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function notify(ApplicationShop $applicationShop): void
    {
        /** @var \SplObserver|ObserverSelectorInterface $observer */
        foreach ($this->observers as $observer) {
            $model = $observer->update($this);

            /** @var FindingApiResponseModelInterface $responseModel */
            $responseModel = $this->findingApiEntryPoint->findItemsInEbayStores($model);

            /** @var RootItem $rootItem */
            $rootItem = $responseModel->getRoot();

            if ($rootItem->getSearchResultsCount() > 0) {
                $this->searchProducts[] = new SearchProduct(
                    $responseModel,
                    $applicationShop
                );

                $this->observers = [];

                break;
            }
        }
    }
    /**
     * @return iterable|TypedArray
     */
    public function getProductResponseModels(): iterable
    {
        return $this->searchProducts;
    }
}