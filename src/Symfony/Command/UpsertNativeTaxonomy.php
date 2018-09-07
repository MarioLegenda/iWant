<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\NativeTaxonomyRepository;
use App\Library\Tools\LockedMutableHashSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpsertNativeTaxonomy extends BaseCommand
{
    /**
     * @var NativeTaxonomyRepository $nativeTaxonomyRepository
     */
    private $nativeTaxonomyRepository;
    /**
     * UpsertNativeTaxonomy constructor.
     * @param NativeTaxonomyRepository $nativeTaxonomyRepository
     */
    public function __construct(
        NativeTaxonomyRepository $nativeTaxonomyRepository
    ) {
        $this->nativeTaxonomyRepository = $nativeTaxonomyRepository;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:upsert_native_taxonomy');

        $this
            ->addArgument('name', InputArgument::REQUIRED)
            ->addArgument('update', InputArgument::OPTIONAL)
        ;
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

        $arguments = $this->resolveArguments();

        $this->upsertTaxonomy($arguments);
    }
    /**
     * @param iterable $arguments
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function upsertTaxonomy(iterable $arguments): void
    {
        $name = $arguments['name'];
        $update = $arguments['update'];

        if ($update) {
            $this->updateTaxonomy($name, $update);

            return;
        }

        $this->createTaxonomy($name);
    }
    /**
     * @param string $name
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createTaxonomy(string $name): void
    {
        /** @var NativeTaxonomy $existingTaxonomy */
        $existingTaxonomy = $this->nativeTaxonomyRepository->findOneBy([
            'name' => $name
        ]);

        if ($existingTaxonomy instanceof NativeTaxonomy) {
            $message = sprintf(
                'Native taxonomy \'%s\' already exists',
                $name
            );

            throw new \RuntimeException($message);
        }

        $nativeTaxonomy = new NativeTaxonomy($name);

        $this->nativeTaxonomyRepository->persistAndFlush($nativeTaxonomy);

        $this->output->writeln(sprintf(
            '<info>Taxonomy %s created. Exiting</info>',
            $name
        ));
    }
    /**
     * @param string $name
     * @param string $update
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function updateTaxonomy(string $name, string $update): void
    {
        /** @var NativeTaxonomy $existingTaxonomy */
        $existingTaxonomy = $this->nativeTaxonomyRepository->findOneBy([
            'name' => $name
        ]);

        if (!$existingTaxonomy instanceof NativeTaxonomy) {
            $message = sprintf(
                'Taxonomy \'%s\' does not exist and cannot be changed',
                $name
            );

            throw new \RuntimeException($message);
        }

        $existingTaxonomy->setName($update);

        $this->nativeTaxonomyRepository->persistAndFlush($existingTaxonomy);

        $this->output->writeln(sprintf(
            '<info>Taxonomy %s updated to %s. Exiting</info>',
            $name,
            $update
        ));
    }
    /**
     * @return LockedMutableHashSet
     */
    private function resolveArguments(): LockedMutableHashSet
    {
        $arguments = LockedMutableHashSet::create([
            'name',
            'update',
        ]);

        $arguments['name'] = $this->input->getArgument('name');

        if (empty($arguments['name'])) {
            $message = sprintf(
                '\'name\' argument cannot be empty'
            );

            throw new \RuntimeException($message);
        }

        $arguments['update'] = $this->input->getArgument('update');

        return $arguments;
    }
}