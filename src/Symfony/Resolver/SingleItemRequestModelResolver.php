<?php

namespace App\Symfony\Resolver;

use App\App\Presentation\Model\Request\SingleItemRequestModel;
use App\Library\MarketplaceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class SingleItemRequestModelResolver implements ArgumentValueResolverInterface
{
    /**
     * @var SingleItemRequestModelResolver $model
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
        if ($argument->getType() !== SingleItemRequestModel::class) {
            return false;
        }

        $marketplace = $request->get('marketplace');
        $itemId = $request->get('itemId');

        if (!is_string($marketplace) or !is_string($itemId)) {
            return false;
        }

        $this->model = new SingleItemRequestModel(
            MarketplaceType::fromValue($marketplace),
            $itemId
        );

        return true;
    }
}