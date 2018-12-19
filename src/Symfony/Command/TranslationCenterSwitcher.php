<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\TranslationCenterSwitch;
use App\Doctrine\Repository\TranslationCenterSwitchRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Translation\TranslationCenterSwitcher as TranslationCenterSwitcherImplementation;

class TranslationCenterSwitcher extends BaseCommand
{
    /**
     * @var TranslationCenterSwitcherImplementation $translationCenterSwitcher
     */
    private $translationCenterSwitcher;
    /**
     * @var array $translationCenters
     */
    private $translationCenters;
    /**
     * @var TranslationCenterSwitchRepository $translationCenterSwitchRepository
     */
    private $translationCenterSwitchRepository;
    /**
     * TranslationCenterSwitcher constructor.
     * @param TranslationCenterSwitcherImplementation $translationCenterSwitcher
     * @param TranslationCenterSwitchRepository $translationCenterSwitchRepository
     * @param array $translationCenters
     */
    public function __construct(
        TranslationCenterSwitcherImplementation $translationCenterSwitcher,
        TranslationCenterSwitchRepository $translationCenterSwitchRepository,
        array $translationCenters
    ) {
        $this->translationCenterSwitcher = $translationCenterSwitcher;
        $this->translationCenters = $translationCenters;
        $this->translationCenterSwitchRepository = $translationCenterSwitchRepository;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:translation_center_switch');

        $this->addArgument('center_name', InputArgument::REQUIRED, 'Name of the translation center');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $translationCenterName = $this->input->getArgument('center_name');

        $this->output->writeln('<info>Validating command...</info>');

        if (!in_array($translationCenterName, $this->translationCenters)) {
            $message = sprintf(
                'Translation center \'%s\' does not exist. Valid translation centers are: %s',
                $translationCenterName,
                implode(', ', $this->translationCenters)
            );

            throw new \RuntimeException($message);
        }

        $translationCenters = $this->translationCenterSwitchRepository->findAll();

        if (empty($translationCenters)) {
            $output->writeln('<info>Found missing translation centers. Updating translation centers...</info>');

            $this->createMissingTranslationCenters();

            $output->writeln(sprintf('<info>Translation centers updated. Moving on to switching to %s</info>', $translationCenterName));
        }

        $this->output->writeln('<info>Executing</info>');

        $this->translationCenterSwitcher->switchTo($translationCenterName);

        $this->output->writeln(sprintf('<info>Translation center switched to %s</info>', $translationCenterName));
    }
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createMissingTranslationCenters(): void
    {
        foreach ($this->translationCenters as $translationCenter) {
            /** @var TranslationCenterSwitch|null $center */
            $center = $this->translationCenterSwitchRepository->findOneBy([
                'name' => $translationCenter,
            ]);

            if (!$center instanceof TranslationCenterSwitch) {
                $newCenter = new TranslationCenterSwitch($translationCenter);

                $this->translationCenterSwitchRepository->persistAndFlush($newCenter);
            }
        }
    }
}