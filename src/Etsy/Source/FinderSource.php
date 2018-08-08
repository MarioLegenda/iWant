<?php

namespace App\Etsy\Source;

use App\Etsy\Source\Repository\EtsyRepository;

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

    public function get(string $url): string
    {
        return $this->etsyRepository->get($url);
    }
}