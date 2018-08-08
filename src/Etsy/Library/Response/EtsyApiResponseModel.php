<?php

namespace App\Etsy\Library\Response;

class EtsyApiResponseModel implements EtsyApiResponseModelInterface
{
    /**
     * EtsyApiResponseModel constructor.
     * @param iterable $response
     */
    public function __construct(iterable $response)
    {
        dump($response);
        die();
    }
}