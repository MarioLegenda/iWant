<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 10/09/2018
 * Time: 12:55
 */

namespace App\Symfony\Resolver;


use App\Library\Util\Util;
use App\Web\Model\Request\TodayProductRequestModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class TodaysProductsModelResolver implements ArgumentValueResolverInterface
{
    /**
     * @var TodayProductRequestModel $model
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
        if ($argument->getType() !== TodayProductRequestModel::class) {
            return false;
        }

        $date = $request->query->get('data');

        $date = Util::toDateTime($date, Util::getSimpleDateApplicationFormat());

        $this->model = new TodayProductRequestModel($date);

        return true;
    }
}