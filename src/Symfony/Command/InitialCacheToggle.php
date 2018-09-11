<?php

namespace App\Symfony\Command;

use App\Doctrine\Repository\ToggleCacheRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Doctrine\Entity\ToggleCache as ToggleCacheEntity;

class InitialCacheToggle extends BaseCommand
{
    /**
     * @var ToggleCacheRepository $toggleCacheRepository
     */
    private $toggleCacheRepository;
    /**
     * ToggleCache constructor.
     * @param ToggleCacheRepository $toggleCacheRepository
     */
    public function __construct(
        ToggleCacheRepository $toggleCacheRepository
    ) {
        parent::__construct();

        $this->toggleCacheRepository = $toggleCacheRepository;
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:initial_cache_toogle');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $this->output->writeln(sprintf(
            '<info>Starting command %s</info>',
            $this->getName()
        ));

        $cacheToggle = $this->toggleCacheRepository->findAll();

        if (!empty($cacheToggle)) {
            $this->output->writeln('');
            $this->output->writeln(sprintf(
                '<comment>Cache toggle is already initialized. Exiting</comment>'
            ));
            $this->output->writeln('');

            return;
        }

        $this->toggleCacheRepository->persistAndFlush($this->createCacheToggle());

        $this->output->writeln(sprintf(
            '<info>Cache toggler has been initialized. Command successful. Exiting</info>'
        ));
    }
    /**
     * @return ToggleCacheEntity
     */
    private function createCacheToggle(): ToggleCacheEntity
    {
        return new ToggleCacheEntity();
    }
}