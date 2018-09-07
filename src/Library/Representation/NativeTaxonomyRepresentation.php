<?php

namespace App\Library\Representation;

use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\NativeTaxonomyRepository;
use App\Library\Tools\LockedMutableHashSet;

class NativeTaxonomyRepresentation
{
    /**
     * @var array|NativeTaxonomy[] $taxonomy
     */
    private $taxonomy;
    /**
     * @var NativeTaxonomyRepository $nativeTaxonomyRepository
     */
    private $nativeTaxonomyRepository;
    /**
     * NativeTaxonomyRepresentation constructor.
     * @param NativeTaxonomyRepository $nativeTaxonomyRepository
     */
    public function __construct(
        NativeTaxonomyRepository $nativeTaxonomyRepository
    ) {
        $this->nativeTaxonomyRepository = $nativeTaxonomyRepository;

        $this->taxonomy = LockedMutableHashSet::create([
            'booksMusicMovies',
            'autopartsMechanics',
            'homeGarden',
            'computerMobileGames',
            'sport',
            'antiquesArtCollectibles',
            'craftsHandmade',
        ]);
    }

    public function booksMusicMovies(): NativeTaxonomy
    {

    }
}