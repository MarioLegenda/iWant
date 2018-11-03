<?php

namespace App\Symfony\Command\Util;

use App\Cache\Implementation\SearchResponseCacheImplementation;
use App\Doctrine\Repository\SearchCacheRepository;
use App\Symfony\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SearchResponseCacheCheck extends BaseCommand
{
    /**
     * @var SearchResponseCacheImplementation $searchCacheImplementation
     */
    private $searchCacheImplementation;
    /**
     * @var SearchCacheRepository $searchCacheRepository
     */
    private $searchCacheRepository;
    /**
     * SearchResponseCacheCheck constructor.
     * @param SearchResponseCacheImplementation $searchResponseCacheImplementation
     * @param SearchCacheRepository $searchCacheRepository
     */
    public function __construct(
        SearchResponseCacheImplementation $searchResponseCacheImplementation,
        SearchCacheRepository $searchCacheRepository
    ) {
        $this->searchCacheImplementation = $searchResponseCacheImplementation;
        $this->searchCacheRepository = $searchCacheRepository;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:search_response_check');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \App\Cache\Exception\CacheException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $uniqueName = md5(rand(0, 999999));
        $page = 1;
        $value = sha1(md5(rand(0, 9999999)));

        $this->searchCacheImplementation->store(
            $uniqueName,
            $page,
            $value
        );

        $response = $this->searchCacheImplementation->getStored($uniqueName);

        if ($response !== $value) {
            $message = sprintf(
                'Command failed. %s does not equal %s for unique name %s',
                $value,
                $response,
                $uniqueName
            );

            throw new \RuntimeException($message);
        }

        $output->writeln('');
        $output->writeln('Command successful. ');
    }
}