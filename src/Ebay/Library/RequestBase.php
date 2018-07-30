<?php

namespace App\Ebay\Library;

use App\Ebay\Library\Tools\LockedImmutableHashSet;

class RequestBase
{
    /**
     * @var LockedImmutableHashSet $ebayFindingApiMetadata
     */
    private $ebayFindingApiMetadata;
    /**
     * RequestBase constructor.
     * @param iterable $ebayFindingApiMetadata
     */
    public function __construct(
        iterable $ebayFindingApiMetadata
    ) {
        $this->ebayFindingApiMetadata = LockedImmutableHashSet::create($ebayFindingApiMetadata);
    }
    /**
     * @param LockedImmutableHashSet $userParams
     * @return string
     */
    public function getBaseUrl(LockedImmutableHashSet $userParams): string {
        $baseUrl = $this->ebayFindingApiMetadata['base_url'];
        $names = $this->ebayFindingApiMetadata['names'];
        $configParams = $this->ebayFindingApiMetadata['params']->toArray();
        $userParams = $userParams->toArray();

        foreach ($names as $key => $name) {
            $currentProduct = '';
            if (array_key_exists($key, $configParams) and is_string($configParams[$key])) {
                $currentProduct.=sprintf(
                    '%s=%s',
                    $name,
                    $configParams[$key]
                );
            }

            if (array_key_exists($key, $configParams)) {
                $param = $configParams[$key];

                if (is_null($param)) {
                    $currentProduct.=sprintf(
                        '%s',
                        $name,
                        $configParams[$key]
                    );
                }
            }

            if (array_key_exists($key, $userParams)) {
                $currentProduct.=sprintf(
                    '%s=%s',
                    $name,
                    $userParams[$key]
                );
            }

            $baseUrl.=$currentProduct.'&';
        }

        return rtrim($baseUrl, '&');
    }
}