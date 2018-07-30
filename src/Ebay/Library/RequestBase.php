<?php

namespace App\Ebay\Library;

class RequestBase
{
    /**
     * @var iterable $ebayFindingApiMetadata
     */
    private $ebayFindingApiMetadata;
    /**
     * @var string $baseUrl
     */
    private $baseUrl;
    /**
     * RequestBase constructor.
     * @param iterable $ebayFindingApiMetadata
     */
    public function __construct(
        iterable $ebayFindingApiMetadata
    ) {
        $this->ebayFindingApiMetadata = $ebayFindingApiMetadata;
    }

    private function buildBaseUrl(): string
    {

    }
}