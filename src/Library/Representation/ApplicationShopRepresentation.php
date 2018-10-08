<?php

namespace App\Library\Representation;

use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Library\Util\Util;

class ApplicationShopRepresentation
{
    /**
     * @var array|ApplicationShop[] $applicationShopCache
     */
    private $applicationShopCache = [];
    /**
     * @var ApplicationShopRepository $applicationShopRepository
     */
    private $applicationShopRepository;
    /**
     * ApplicationShopRepresentation constructor.
     * @param ApplicationShopRepository $applicationShopRepository
     */
    public function __construct(
        ApplicationShopRepository $applicationShopRepository
    ) {
        $this->applicationShopRepository = $applicationShopRepository;
    }
    /**
     * @param string $key
     * @param string $name
     * @return ApplicationShop
     */
    public function getShop(string $key, string $name): ApplicationShop
    {
        $cached = $this->findInCache($name);

        if (!$cached instanceof ApplicationShop) {
            /** @var ApplicationShop $applicationShop */
            $applicationShop = $this->applicationShopRepository->findOneBy([
                $key => $name,
            ]);

            $this->applicationShopCache[] = $applicationShop;

            return $applicationShop;
        }

        return $cached;
    }
    /**
     * @param string $value
     * @return ApplicationShop|null
     */
    private function findInCache(string $value): ?ApplicationShop
    {
        if (empty($this->applicationShopCache)) {
            return null;
        }

        $applicationShopsGen = Util::createGenerator($this->applicationShopCache);

        foreach ($applicationShopsGen as $entry) {
            /** @var ApplicationShop $item */
            $item = $entry['item'];

            $applicationShopArray = $item->toArray();

            if (in_array($value, $applicationShopArray) === true) {
                return $item;
            }
        }

        return null;
    }
}