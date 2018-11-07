<?php

namespace App\Symfony\Resolver;

use App\App\Presentation\Model\Request\SingleItemRequestModel;
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

        if ($request->get('_route') !== 'app_put_single_item') {
            return false;
        }

        $content = $request->getContent();

        if (empty($content)) {
            return false;
        }

        $decodedContent = json_decode($content, true);

        if (!array_key_exists('itemId', $decodedContent)) {
            return false;
        }

        $itemId = $decodedContent['itemId'];

        $this->model = new SingleItemRequestModel(
            $itemId
        );

        return true;
    }
}