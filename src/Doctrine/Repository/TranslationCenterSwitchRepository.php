<?php

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\TranslationCenterSwitch;
use App\Library\Util\Util;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TranslationCenterSwitchRepository extends ServiceEntityRepository
{
    /**
     * @var array $scheduledToFlush
     */
    private $scheduledToFlush = [];
    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TranslationCenterSwitch::class);
    }
    /**
     * @param TranslationCenterSwitch $translationCenterSwitch
     * @return TranslationCenterSwitch
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(TranslationCenterSwitch $translationCenterSwitch): TranslationCenterSwitch
    {
        $this->getEntityManager()->persist($translationCenterSwitch);
        $this->getEntityManager()->flush();

        return $translationCenterSwitch;
    }
    /**
     * @param TranslationCenterSwitch $translationCenterSwitch
     */
    public function scheduleToFlush(TranslationCenterSwitch $translationCenterSwitch): void
    {
        $this->scheduledToFlush[] = $translationCenterSwitch;
    }
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function atomicPersist(): void
    {
        $scheduledToFlushGen = Util::createGenerator($this->scheduledToFlush);

        foreach ($scheduledToFlushGen as $entry) {
            /** @var TranslationCenterSwitch $item */
            $item = $entry['item'];

            $this->getEntityManager()->persist($item);
        }

        $this->getEntityManager()->flush();
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager()
    {
        return parent::getEntityManager();
    }
}