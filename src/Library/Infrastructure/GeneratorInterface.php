<?php

namespace App\Library\Infrastructure;

interface GeneratorInterface
{
    /**
     * @return \Generator
     */
    public function createGenerator(): \Generator;
}