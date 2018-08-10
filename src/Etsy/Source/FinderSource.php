<?php

namespace App\Etsy\Source;

use App\Etsy\Source\Repository\EtsyRepository;
use App\Library\Http\Request;

class FinderSource
{
    /**
     * @var EtsyRepository $finderRepository
     */
    private $etsyRepository;
    /**
     * FinderSource constructor.
     * @param EtsyRepository $etsyRepository
     */
    public function __construct(
        EtsyRepository $etsyRepository
    ) {
        $this->etsyRepository = $etsyRepository;
    }
    /**
     * @param Request $request
     * @return string
     */
    public function getResource(Request $request): string
    {
        return $this->etsyRepository->getResource($request);
    }
}