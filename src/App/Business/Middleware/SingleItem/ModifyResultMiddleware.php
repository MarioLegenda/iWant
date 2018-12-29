<?php

namespace App\App\Business\Middleware\SingleItem;

use App\App\Business\Middleware\MiddlewareResult;
use App\Library\Middleware\MiddlewareEntryInterface;
use App\Library\Middleware\MiddlewareResultInterface;

class ModifyResultMiddleware implements MiddlewareEntryInterface
{
    /**
     * @param MiddlewareResultInterface|null $middlewareResult
     * @param array|null $parameters
     * @return MiddlewareResultInterface
     */
    public function handle(
        MiddlewareResultInterface $middlewareResult = null,
        array $parameters = null
    ): MiddlewareResultInterface {
        if (!$middlewareResult->isFulfilled()) {
            return $middlewareResult;
        }

        $result = $middlewareResult->getResult();

        if (count($result['shipsToLocations']) === 1 and $result['shipsToLocations'][0] === 'Worldwide') {
            $result['shipsToLocations'] = [
                'isWorldwide' => true,
                'locations' => ['Worldwide'],
            ];
        } else if (count($result['shipsToLocations']) > 1) {
            $result['shipsToLocations'] = [
                'isWorldwide' => false,
                'locations' => $result['shipsToLocations'],
            ];
        }

        return new MiddlewareResult($result, true);
    }
}