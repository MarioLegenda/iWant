<?php

namespace App\Etsy\Library\Response;

use App\Etsy\Library\Response\ResponseItem\ResultsInterface;

interface EtsyApiResponseModelInterface
{
    /**
     * @return int
     */
    public function getCount(): int;
    /**
     * @return ResultsInterface
     */
    public function getResults(): ResultsInterface;
}