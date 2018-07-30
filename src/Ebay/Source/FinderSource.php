<?php

namespace App\Ebay\Source;

use App\Ebay\Library\RequestBase;

class FinderSource
{
    /**
     * @var RequestBase $requestBase
     */
    private $requestBase;
    /**
     * FinderSource constructor.
     * @param RequestBase $requestBase
     */
    public function __construct(
        RequestBase $requestBase
    ) {
        $this->requestBase = $requestBase;
    }


}