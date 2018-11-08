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

        $data = $this->extractDataFromUrl($request);

        if (!is_array($data)) {
            return false;
        }

        $this->model = new SingleItemOptionsModel(
            $data['itemId'],
            $data['locale']
        );

        return true;
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