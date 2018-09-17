<?php

namespace App\Symfony\Resolver;

use App\App\Presentation\Model\SingleItemRequestModel;
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
        if ($argument->getType() !== SingleItemRequestModelResolver::class) {
            return false;
        }

        if (!$request->query->has('marketplace') or !$request->query->has('itemId')) {
            return false;
        }

        $marketplace = $request->query->get('marketplace');
        $itemId = (string) $request->query->get('itemId');

        $this->model = new SingleItemRequestModel(
            MarketplaceType::fromValue($marketplace),
            $itemId
        );

        return true;
    }
}