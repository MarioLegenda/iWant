<?php

namespace App\Symfony\Resolver;

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

        $uniqueName = $request->get('uniqueName');

        if (is_null($uniqueName)) {
            return false;
        }

        $this->model = new PreparedItemsSearchModel($uniqueName);

        return true;
    }
}