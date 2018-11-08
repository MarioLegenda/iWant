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

        $supportedRoutes = [
            'app_put_single_item',
            'app_get_single_item',
        ];

        if (!in_array($request->get('_route'), $supportedRoutes)) {
            return false;
        }

        $itemId = null;
        if ($request->get('_route') === 'app_put_single_item') {
            $itemId = $this->extractItemIdFromBody($request);
        } else if ($request->get('_route') === 'app_get_single_item') {
            $itemId = $this->extractItemIdFromUrl($request);
        }

        if (!is_string($itemId)) {
            return false;
        }

        $this->model = new SingleItemRequestModel(
            $itemId
        );

        return true;
    }
    /**
     * @param Request $request
     * @return null|string
     */
    private function extractItemIdFromBody(Request $request): ?string
    {
        $content = $request->getContent();

        if (empty($content)) {
            return null;
        }

        $decodedContent = json_decode($content, true);

        if (!array_key_exists('itemId', $decodedContent)) {
            return null;
        }

        return (string) $decodedContent['itemId'];
    }
    /**
     * @param Request $request
     * @return null|string
     */
    private function extractItemIdFromUrl(Request $request): ?string
    {
        $itemId = $request->get('itemId');

        if (!is_string($itemId)) {
            return null;
        }

        return $itemId;
    }
}