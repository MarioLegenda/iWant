<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\NormalizedCategoryRepository;
use App\Library\Tools\LockedMutableHashSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpsertNormalizedCategory extends BaseCommand
{
    /**
     * @var NormalizedCategoryRepository $normalizedCategoryRepository
     */
    private $normalizedCategoryRepository;
    /**
     * UpsertNormalizedCategory constructor.
     * @param NormalizedCategoryRepository $normalizedCategoryRepository
     */
    public function __construct(
        NormalizedCategoryRepository $normalizedCategoryRepository
    ) {
        $this->normalizedCategoryRepository = $normalizedCategoryRepository;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:upsert_normalized_category');

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

        $this->upsertCategory($arguments);
    }
    /**
     * @param iterable $arguments
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function upsertCategory(iterable $arguments): void
    {
        $name = $arguments['name'];
        $update = $arguments['update'];

        if ($update) {
            $this->updateCategory($name, $update);

            return;
        }

        $this->createCategory($name);
    }
    /**
     * @param string $name
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createCategory(string $name): void
    {
        /** @var NativeTaxonomy $existingCategory */
        $existingCategory = $this->normalizedCategoryRepository->findOneBy([
            'name' => $name
        ]);

        if ($existingCategory instanceof NativeTaxonomy) {
            $message = sprintf(
                'Normalized category \'%s\' already exists',
                $name
            );

            throw new \RuntimeException($message);
        }

        $normalizedCategory = new NativeTaxonomy($name);

        $this->normalizedCategoryRepository->persistAndFlush($normalizedCategory);

        $this->output->writeln(sprintf(
            '<info>Category %s created. Exiting</info>',
            $name
        ));
    }
    /**
     * @param string $name
     * @param string $update
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function updateCategory(string $name, string $update): void
    {
        /** @var NativeTaxonomy $existingCategory */
        $existingCategory = $this->normalizedCategoryRepository->findOneBy([
            'name' => $name
        ]);

        if (!$existingCategory instanceof NativeTaxonomy) {
            $message = sprintf(
                'Normalized category \'%s\' does not exist and cannot be changed',
                $name
            );

            throw new \RuntimeException($message);
        }

        $existingCategory->setName($update);

        $this->normalizedCategoryRepository->persistAndFlush($existingCategory);

        $this->output->writeln(sprintf(
            '<info>Category %s updated to %s. Exiting</info>',
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