<?php

namespace App\Symfony\Resolver;

use App\Web\Model\Request\UniformedRequestModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UniformedRequestModelResolver implements ArgumentValueResolverInterface
{
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

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if ($argument->getType() !== UniformedRequestModel::class) {
            return false;
        }

        $data = json_decode($request->query->get('data'), true);

        $this->model = new UniformedRequestModel(
            $data['keywords'],
            $data['filters']
        );

        return true;
    }
}