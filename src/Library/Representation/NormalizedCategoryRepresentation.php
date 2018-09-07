<?php

namespace App\Library\Representation;

use App\Doctrine\Entity\NormalizedCategory;
use App\Doctrine\Repository\NormalizedCategoryRepository;

class NormalizedCategoryRepresentation
{
    /**
     * @var NormalizedCategoryRepository $normalizedCategoryRepository
     */
    private $normalizedCategoryRepository;
    /**
     * NormalizedCategoryRepresentation constructor.
     * @param NormalizedCategoryRepository $normalizedCategoryRepository
     */
    public function __construct(
        NormalizedCategoryRepository $normalizedCategoryRepository
    ) {
        $this->normalizedCategoryRepository = $normalizedCategoryRepository;
    }
}