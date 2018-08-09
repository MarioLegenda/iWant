<?php

namespace App\Etsy\Library\Response;

use App\Etsy\Library\Response\ResponseItem\Results;

interface EtsyApiResponseModelInterface
{
    /**
     * @return int
     */
    public function getCount(): int;
    /**
     * @return Results
     */
    public function getResults(): Results;
}