<?php

namespace App\Translation;

use App\Doctrine\Entity\TranslationCenterSwitch;
use App\Doctrine\Repository\TranslationCenterSwitchRepository;

class TranslationCenterSwitcher
{
    /**
     * @var TranslationCenterSwitchRepository $translationCenterSwitchRepository
     */
    private $translationCenterSwitchRepository;
    /**
     * @var array $translationCenters
     */
    private $translationCenters;
    /**
     * TranslationCenterSwitcher constructor.
     * @param TranslationCenterSwitchRepository $translationCenterSwitchRepository
     * @param array $translationCenters
     */
    public function __construct(
        TranslationCenterSwitchRepository $translationCenterSwitchRepository,
        array $translationCenters
    ) {
        $this->translationCenterSwitchRepository = $translationCenterSwitchRepository;
        $this->translationCenters = $translationCenters;
    }
    /**
     * @param string $translationCenter
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function switchTo(string $translationCenter): void
    {
        if (!in_array($translationCenter, $this->translationCenters)) {
            $message = sprintf(
                'Unknown translation center %s given. Valid translation centers are: %s',
                $translationCenter,
                implode(', ', $this->translationCenters)
            );

            throw new \RuntimeException($message);
        }

        $translationCenters = $this->translationCenterSwitchRepository->findBy([
            'enabled' => true,
        ]);

        if (count($translationCenters) > 1) {
            $message = sprintf(
                'Multiple enabled translation centers found. There can be only one enabled active translation center'
            );

            throw new \RuntimeException($message);
        }

        if (empty($translationCenters)) {
            /** @var TranslationCenterSwitch $foundCenter */
            $foundCenter = $this->translationCenterSwitchRepository->findOneBy([
                'name' => $translationCenter
            ]);

            $foundCenter->setEnabled(true);

            $this->translationCenterSwitchRepository->persistAndFlush($foundCenter);

            return;
        }

        /** @var TranslationCenterSwitch $enabledCenter */
        $enabledCenter = $translationCenters[0];

        if ($enabledCenter->getName() === $translationCenter) {
            return;
        }

        $enabledCenter->setEnabled(false);

        $centerToEnable = $this->translationCenterSwitchRepository->findOneBy([
            'name' => $translationCenter
        ]);

        if (!$centerToEnable instanceof TranslationCenterSwitch) {
            $message = sprintf(
                'Translation center with name %s not found',
                $translationCenter
            );

            throw new \RuntimeException($message);
        }

        $centerToEnable->setEnabled(true);

        $this->translationCenterSwitchRepository->scheduleToFlush($enabledCenter);
        $this->translationCenterSwitchRepository->scheduleToFlush($centerToEnable);

        $this->translationCenterSwitchRepository->atomicPersist();


    }
}