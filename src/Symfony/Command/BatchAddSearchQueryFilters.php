<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\SearchQueryFilter;
use App\Doctrine\Repository\SearchQueryFilterRepository;
use App\Library\Util\Util;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BatchAddSearchQueryFilters extends BaseCommand
{
    /**
     * @var array $entries
     */
    private $entries = [];
    /**
     * @var SearchQueryFilterRepository $searchQueryFilterRepository
     */
    private $searchQueryFilterRepository;
    /**
     * BatchAddSearchQueryFilters constructor.
     * @param SearchQueryFilterRepository $searchQueryFilterRepository
     */
    public function __construct(
        SearchQueryFilterRepository $searchQueryFilterRepository
    ) {
        parent::__construct();

        $this->searchQueryFilterRepository = $searchQueryFilterRepository;

        $this->entries = [
            [
                'reference' => 'iphone',
                'values' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag']
            ],
            [
                'reference' => 'samsung',
                'values' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag']
            ],
            [
                'reference' => 'huawei',
                'values' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag']
            ],
            [
                'reference' => 'zte',
                'values' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag']
            ],
            [
                'reference' => 'google pixel',
                'values' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag']
            ],
            [
                'reference' => 'xiaomi',
                'values' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag']
            ],
            [
                'reference' => 'oppo',
                'values' => ['mask', 'case', 'protective', 'ring', 'holder', 'cover', 'purse', 'handbag']
            ],
        ];
    }
    /**
     * @void
     */
    public function configure()
    {
        $this->setName('app:batch_add_search_query_filters');
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

        $this->output->writeln(sprintf(
            '<info>Starting command...</info>'
        ));

        $entries = Util::createGenerator($this->entries);
        foreach ($entries as $entry) {
            $item = $entry['item'];

            $existingSearchQueryFilter = $this->searchQueryFilterRepository->findBy([
                'reference' => $item['reference'],
            ]);

            if (!empty($existingSearchQueryFilter)) {
                $message = sprintf(
                    '%s already exists with reference %s',
                    get_class($this),
                    $item['reference']
                );

                throw new \RuntimeException($message);
            }

            $searchQueryFilter = new SearchQueryFilter(
                $item['reference'],
                jsonEncodeWithFix($item['values'])
            );

            $this->searchQueryFilterRepository->persistAndFlush($searchQueryFilter);
        }

        $output->writeln('');
        $output->writeln(sprintf(
            '<info>Successfully executed command</info>'
        ));
    }
}