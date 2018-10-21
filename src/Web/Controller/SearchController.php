<?php

namespace App\Web\Controller;

use App\Component\Search\Ebay\Model\Request\SearchModel as EbaySearchModel;
use App\Component\Search\Etsy\Model\Request\SearchModel as EtsySearchModel;
use App\Component\Search\SearchComponent;
use App\Library\Http\Response\ApiResponseData;
use App\Library\Http\Response\ApiSDK;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Environment;
use App\Library\Util\TypedRecursion;
use App\Web\Library\View\EbaySearchViewType;
use App\Web\Library\View\StaticViewFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController
{
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * AppController constructor.
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        ApiSDK $apiSDK
    ) {
        $this->apiSdk = $apiSDK;
    }
    /**
     * @param EbaySearchModel $model
     * @param SearchComponent $searchComponent
     * @param Environment $environment
     * @return JsonResponse
     * @throws \App\Cache\Exception\CacheException
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Http\Client\Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getEbaySearch(
        EbaySearchModel $model,
        SearchComponent $searchComponent,
        Environment $environment
    ): JsonResponse {
        /** @var iterable|array $products */
        $products = $searchComponent->searchEbay($model);

        $this->addRequiredViews($model, $products);

        /** @var ApiResponseData $responseData */
        $responseData = $this->apiSdk
            ->method('GET')
            ->addMessage('A search result')
            ->isCollection()
            ->addPagination($model->getPagination()->getLimit(), $model->getPagination()->getPage())
            ->setStatusCode(200)
            ->build();

        $response = new JsonResponse(
            $responseData->toArray(),
            $responseData->getStatusCode()
        );

        if ((string) $environment === 'dev') {
            $response->headers->set('Cache-Control', 'no-cache');
        } else if ((string) $environment === 'prod') {
            $response->setMaxAge(86400);
        }

        return $response;
    }

    public function getEtsySearch(
        EtsySearchModel $model,
        SearchComponent $searchComponent,
        Environment $environment
    ) {
        /** @var TypedArray $products */
        $products = $searchComponent->searchEtsy($model);

        /** @var ApiResponseData $responseData */
        $responseData = $this->apiSdk
            ->create($products->toArray(TypedRecursion::RESPECT_ARRAY_NOTATION))
            ->method('GET')
            ->addMessage('A search result')
            ->isCollection()
            ->addPagination($model->getPagination()->getLimit(), $model->getPagination()->getPage())
            ->setStatusCode(200)
            ->build();

        $response = new JsonResponse(
            $responseData->toArray(),
            $responseData->getStatusCode()
        );

        if ((string) $environment === 'dev') {
            $response->headers->set('Cache-Control', 'no-cache');
        } else if ((string) $environment === 'prod') {
            $response->setMaxAge(86400);
        }

        return $response;
    }
    /**
     * @param EbaySearchModel $model
     * @param array $products
     */
    private function addRequiredViews(EbaySearchModel $model, array $products)
    {
        if ((string) $model->getViewType() === (string) EbaySearchViewType::fromValue('globalIdView')) {
            $this->apiSdk->create([]);
            $this->apiSdk->addView((string) $model->getViewType(), StaticViewFactory::createGlobalIdView($products));
        } else if ((string) $model->getViewType() === (string) EbaySearchViewType::fromValue('itemsView')) {
            $this->apiSdk->create([]);
            $this->apiSdk->addView((string) $model->getViewType(), StaticViewFactory::createItemsView($products));
        } else if ((string) $model->getViewType() === (string) EbaySearchViewType::fromValue('default')) {
            $this->apiSdk->create($products);
        } else {
            $message = sprintf(
                'View type remained unselected for Ebay search request'
            );

            throw new \RuntimeException($message);
        }
    }
}