<?php

namespace App\Web\Factory;

use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Web\Model\Response\UniformedResponseModel;

class EtsyModelFactory implements ModelFactoryInterface
{
    /**
     * @param EtsyApiResponseModelInterface $model
     * @return TypedArray
     */
    public function createModels($model): TypedArray
    {
        
    }
    /**
     * @param $model
     * @return UniformedResponseModel
     */
    private function createModel($model): UniformedResponseModel
    {
        $this->validate($model);
    }
    /**
     * @param EtsyApiResponseModelInterface $model
     */
    private function validate($model)
    {
        if (!$model instanceof EtsyApiResponseModelInterface) {
            $message = sprintf(
                '%s::createModel() has to receive an instance of %s',
                get_class($this),
                EtsyApiResponseModelInterface::class
            );

            throw new \RuntimeException($message);
        }
    }
}