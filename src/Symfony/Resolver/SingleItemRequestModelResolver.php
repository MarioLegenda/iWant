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
            'app_get_quick_look_single_item',
            'app_get_single_item',
        ];

        if (!in_array($request->get('_route'), $supportedRoutes)) {
            return false;
        }

        $data = null;
        if ($request->get('_route') === 'app_put_single_item') {
            $data = $this->extractItemIdFromBody($request);
        } else if (
            $request->get('_route') === 'app_get_quick_look_single_item' OR
            $request->get('_route') === 'app_get_single_item'
        ) {
            $data = $this->extractDataFromUrl($request);
        }

        if (!is_array($data)) {
            return false;
        }

        $this->model = new SingleItemRequestModel(
            $data['itemId'],
            $data['locale']
        );

        return true;
    }
    /**
     * @param Request $request
     * @return null|array
     */
    private function extractItemIdFromBody(Request $request): ?array
    {
        $content = $request->getContent();

        if (empty($content)) {
            return null;
        }

        $decodedContent = json_decode($content, true);

        if (!array_key_exists('itemId', $decodedContent) OR
            !array_key_exists('locale', $decodedContent)) {
            return null;
        }

        return [
            'itemId' => $decodedContent['itemId'],
            'locale' => $decodedContent['locale']
        ];
    }
    /**
     * @param Request $request
     * @return null|array
     */
    private function extractDataFromUrl(Request $request): ?array
    {
        $itemId = $request->get('itemId');
        $locale = $request->get('locale');
        if (!is_string($itemId) OR !is_string($locale)) {
            return null;
        }

        return [
            'itemId' => $itemId,
            'locale' => $locale,
        ];
    }
}