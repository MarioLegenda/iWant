<?php

namespace App\Symfony\Command;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompleteCacheRemove extends BaseCommand
{
    /**
     * @var ManagerRegistry $doctrine
     */
    private $doctrine;
    /**
     * CompleteCacheRemove constructor.
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:complete_cache_remove');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $conn = $this->doctrine->getConnection();

        $caches = [
            'search_cache',
            'item_translation_cache',
            'single_product_item',
            'keyword_translation_cache',
        ];

        $this->output->writeln('');
        $this->output->writeln(sprintf(
            '<info>Removing \'%s\' caches</info>',
            implode(', ', $caches)
        ));

        foreach ($caches as $cache) {
            $command = sprintf('TRUNCATE %s', $cache);

            $conn->exec($command);
        }

        $this->output->writeln('');
        $this->output->writeln(sprintf(
            '<info>Successfully removed cache. Command successful</info>'
        ));
        $this->output->writeln('');
    }
}