<?php

namespace App\Symfony\Resolver;

use App\Component\Search\Ebay\Model\Request\Pagination;
use App\Component\Search\Ebay\Model\Request\PreparedItemsSearchModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class PreparedItemsEbaySearchModelResolver implements ArgumentValueResolverInterface
{
    /**
     * @var PreparedItemsSearchModel $model
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
        if ($argument->getType() !== PreparedItemsSearchModel::class) {
            return false;
        }

        $searchData = $request->get('searchData');

        if (is_null($searchData)) {
            return false;
        }

        $searchData = json_decode($searchData, true)['searchData'];

        $this->model = new PreparedItemsSearchModel(
            $searchData['uniqueName'],
            $searchData['lowestPrice'],
            new Pagination($searchData['pagination']['limit'], $searchData['pagination']['page'])
        );

        return true;
    }
}