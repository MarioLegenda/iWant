<?php

namespace App\Library\Infrastructure\Notation;

use App\Library\Util\TypedRecursion;

interface ArrayNotationInterface
{
    /**
     * @return iterable
     */
    public function toArray(): iterable;
}