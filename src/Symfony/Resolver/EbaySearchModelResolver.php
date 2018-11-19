<?php

namespace App\Symfony\Resolver;

use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\SearchModel;
use App\Library\MarketplaceType;
use App\Web\Library\View\EbaySearchViewType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class EbaySearchModelResolver implements ArgumentValueResolverInterface
{
    /**
     * @var SearchModel $model
     */
    private $model;
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool|\Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->model;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if ($argument->getType() !== SearchModel::class) {
            return false;
        }

        $searchData = $request->get('searchData');

        if (empty($searchData) and !is_string($searchData)) {
            $searchData = $request->getContent();

            $searchData = json_decode($searchData, true)['searchData'];
        } else {
            $searchData = json_decode($searchData, true);
        }

        if (is_null($searchData)) {
            return false;
        }

        $filters = $searchData['filters'];
        $keyword = $searchData['keyword'];
        $pagination = $searchData['pagination'];
        $globalId = $searchData['globalId'];
        $locale = $searchData['locale'];
        $internalPagination = $searchData['internalPagination'];

        $this->model = new SearchModel(
            $keyword,
            $filters['lowestPrice'],
            $filters['highestPrice'],
            $filters['highQuality'],
            $filters['bestMatch'],
            $filters['shippingCountries'],
            $filters['marketplaces'],
            $filters['taxonomies'],
            new Pagination($pagination['limit'], $pagination['page']),
            $globalId,
            $locale,
            new Pagination($internalPagination['limit'], $internalPagination['page'])
        );

        return true;
    }
}