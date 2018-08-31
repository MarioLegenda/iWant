<?php

namespace App\Web\Controller;

use App\Web\UniformedEntryPoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Web\Model\Request\UniformedRequestModel;

class UniformedRequestController
{
    /**
     * @var UniformedEntryPoint $uniformedEntryPoint
     */
    private $uniformedEntryPoint;
    /**
     * UniformedRequestController constructor.
     * @param UniformedEntryPoint $uniformedEntryPoint
     */
    public function __construct(
        UniformedEntryPoint $uniformedEntryPoint
    ) {
        $this->uniformedEntryPoint = $uniformedEntryPoint;
    }
    /**
     * @param UniformedRequestModel $model
     * @return Response
     */
    public function search(UniformedRequestModel $model): Response
    {
        $presentationModels = $this->uniformedEntryPoint->getPresentationModels($model);

        $response = new JsonResponse($presentationModels);

        $response->setCache([
            'max_age' => 60 * 60,
        ]);

        return $response;
    }
}