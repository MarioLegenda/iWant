<?php

namespace App\Web\Factory\FindingApi;

use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Web\Factory\ModelFactoryInterface;
use App\Web\Model\Response\UniformedResponseModel;

class FindingApiModelFactory implements ModelFactoryInterface
{
    /**
     * @param FindingApiResponseModelInterface $model
     * @return TypedArray
     */
    public function createModels($model): TypedArray
    {

    }
    /**
     * @param $model
     * @return UniformedResponseModel
     */
    public function createModel($model): UniformedResponseModel
    {
        $this->validate($model);
    }
    /**
     * @param FindingApiResponseModelInterface $model
     */
    private function validate($model)
    {
        if (!$model instanceof FindingApiResponseModelInterface) {
            $message = sprintf(
                '%s::createModel() has to receive an instance of %s',
                get_class($this),
                FindingApiResponseModelInterface::class
            );

            throw new \RuntimeException($message);
        }
    }
}