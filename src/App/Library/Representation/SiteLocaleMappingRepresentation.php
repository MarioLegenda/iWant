<?php

namespace App\App\Library\Representation;

class SiteLocaleMappingRepresentation
{
    /**
     * @var array $mapping
     */
    private $mapping;
    /**
     * SiteLocaleMappingRepresentation constructor.
     * @param array $mapping
     */
    public function __construct(
        array $mapping
    ) {
        $this->mapping = $mapping;
    }
    /**
     * @param string $globalId
     * @return array
     */
    public function getLocaleByGlobalId(string $globalId): array
    {
        return $this->mapping[$globalId];
    }
}