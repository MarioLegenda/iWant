<?php

namespace App\Web\Model\Response;

use App\Library\Infrastructure\Type\TypeInterface;

interface DeferrableHttpDataObjectInterface
{
    /**
     * @return TypeInterface
     */
    public function getDeferrableType(): TypeInterface;
    /**
     * @return iterable
     */
    public function getDeferrableData(): iterable;
}