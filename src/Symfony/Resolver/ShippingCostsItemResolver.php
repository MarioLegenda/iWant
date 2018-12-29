<?php

namespace App\Symfony\Resolver;

use App\App\Presentation\Model\Request\ItemShippingCostsRequestModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ShippingCostsItemResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ItemShippingCostsRequestModel $model
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
        if ($argument->getType() !== ItemShippingCostsRequestModel::class) {
            return false;
        }

        $data = $this->extractDataFromUrl($request);

        if (!is_array($data)) {
            return false;
        }

        $this->model = new ItemShippingCostsRequestModel(
            $data['itemId'],
            $data['locale'],
            $data['destinationCountryCode']
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
        $destinationCountryCode = $request->get('destinationCountryCode');

        if (!is_string($itemId) OR !is_string($locale) OR !is_string($destinationCountryCode)) {
            return null;
        }

        return [
            'destinationCountryCode' => $destinationCountryCode,
            'itemId' => $itemId,
            'locale' => $locale,
        ];
    }
}