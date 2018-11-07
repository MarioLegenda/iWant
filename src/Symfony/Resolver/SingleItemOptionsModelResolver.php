<?php

namespace App\Symfony\Resolver;

use App\App\Presentation\Model\Request\SingleItemOptionsModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class SingleItemOptionsModelResolver implements ArgumentValueResolverInterface
{
    /**
     * @var SingleItemOptionsModel $model
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
        if ($argument->getType() !== SingleItemOptionsModel::class) {
            return false;
        }

        if ($request->get('_route') !== 'app_options_check_single_item') {
            return false;
        }

        $itemId = (string) $request->get('itemId');

        if (!is_string($itemId)) {
            return false;
        }

        $this->model = new SingleItemOptionsModel($itemId);

        return true;
    }
}