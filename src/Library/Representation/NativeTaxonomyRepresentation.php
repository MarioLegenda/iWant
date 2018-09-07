<?php

namespace App\Library\Representation;

use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\NativeTaxonomyRepository;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class NativeTaxonomyRepresentation implements ArrayNotationInterface, \IteratorAggregate
{
    /**
     * @var TypedArray|iterable $taxonomies
     */
    private $taxonomies;
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
        $this->taxonomies = TypedArray::create('string', NativeTaxonomy::class);
    }
    /**
     * @param string $name
     * @return NativeTaxonomy
     * @throws \RuntimeException
     */
    public function __get(string $name): NativeTaxonomy
    {
        $this->populateTaxonomy($name);

        return $this->taxonomies[$name];
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->toArray());
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        $taxonomies = $this->nativeTaxonomyRepository->findAll();

        /** @var NativeTaxonomy $taxonomy */
        foreach ($taxonomies as $taxonomy) {
            $this->taxonomies[$taxonomy->getName()] = $taxonomy;
        }

        return $taxonomies;
    }

    /**
     * @param string $name
     * @throws \RuntimeException
     */
    private function populateTaxonomy(string $name): void
    {
        if (!isset($this->taxonomies[$name])) {
            $taxonomy = $this->nativeTaxonomyRepository->findOneBy([
                'internalName' => $name,
            ]);

            if (!$taxonomy instanceof NativeTaxonomy) {
                $message = sprintf(
                    'Native taxonomy \'%s\' not found. Check the taxonomies internal name',
                    $name
                );

                throw new \RuntimeException($message);
            }

            $this->taxonomies[$name] = $taxonomy;
        }

        if (!isset($this->taxonomies[$name])) {
            $message = sprintf(
                'Failed to lazy load a native taxonomy with name \'%s\' in %s',
                $name,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }
    }
}