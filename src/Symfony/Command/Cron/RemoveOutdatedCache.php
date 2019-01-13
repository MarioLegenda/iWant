<?php

namespace App\Symfony\Command\Cron;

use App\Doctrine\Entity\SearchCache;
use App\Doctrine\Repository\SearchCacheRepository;
use App\Library\Slack\Metadata;
use App\Library\Slack\SlackClient;
use App\Library\Util\ExceptionCatchWrapper;
use App\Library\Util\Util;
use App\Symfony\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveOutdatedCache extends BaseCommand
{
    /**
     * @var SearchCacheRepository $searchCacheRepository
     */
    private $searchCacheRepository;
    /**
     * @var SlackClient $slackClient
     */
    private $slackClient;
    /**
     * RemoveOutdatedCache constructor.
     * @param SearchCacheRepository $searchCacheRepository
     * @param SlackClient $slackClient
     */
    public function __construct(
        SearchCacheRepository $searchCacheRepository,
        SlackClient $slackClient
    ) {
        parent::__construct();

        $this->slackClient = $slackClient;
        $this->searchCacheRepository = $searchCacheRepository;
    }
    /**
     * @void
     */
    public function configure()
    {
        $this->setName('app:remove_outdated_cache');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $scriptStartTime = time();
        $maxScriptExecTime = 300;

        ExceptionCatchWrapper::run(function() {
            $this->slackClient->send(new Metadata(
                sprintf('Executing %s command', $this->getName()),
                '#cron_jobs',
                [sprintf('Started %s command and is currently executing it', $this->getName())]
            ));
        });

        $limit = 100;
        $offset = 1;

        $this->output->writeln(sprintf(
            '<info>Started the command with limit %d and offset %d</info>',
            $limit,
            $offset
        ));

        $results = [];

        $totalNumFilesDeleted = 0;
        while ($results !== null) {
            $results = $this->getPaginatedCacheEntries($limit, $offset);

            if ($results === null) {
                $this->output->writeln(sprintf(
                    '<info>Found no results in the cache with limit %d and offset %d. Exiting the loop</info>',
                    $limit,
                    $offset
                ));

                $this->outputMemoryUsage();

                break;
            }

            $this->output->writeln('');
            $this->output->writeln(sprintf(
                '<info>Starting the deletion loop with %d number of results</info>',
                $results['totalItems']
            ));

            $this->outputMemoryUsage();

            $numberOfScheduledEntities = 0;
            $resultsGen = $results['generator'];

            foreach ($resultsGen as $entry) {
                /** @var SearchCache $item */
                $item = $entry['item'];

                $expiresAt = $item->getExpiresAt();

                $currentTimestamp = Util::toDateTime()->getTimestamp();

                $ttlTimestamp = $currentTimestamp - $expiresAt;

                if ($ttlTimestamp >= 0) {
                    ++$numberOfScheduledEntities;
                    $this->searchCacheRepository->getManager()->remove($item);
                }
            }

            $this->output->writeln('');
            $this->output->writeln(sprintf(
                '<info>Finished results iteration. %d number of entities scheduled for deletion</info>',
                $numberOfScheduledEntities
            ));

            $this->outputMemoryUsage();

            $this->searchCacheRepository->getManager()->flush();

            $this->output->writeln(sprintf(
                '<info>Removed %d scheduled files. Increasing offset and moving on</info>',
                $numberOfScheduledEntities
            ));

            $this->outputMemoryUsage();

            $totalNumFilesDeleted = $totalNumFilesDeleted + $numberOfScheduledEntities;

            $offset = $offset + $limit;

            $this->output->writeln('');
            $this->output->writeln('');

            $currentTime = time();
            $executionTime = $currentTime - $scriptStartTime;

            if ($executionTime >= $maxScriptExecTime) {
                ExceptionCatchWrapper::run(function() use ($maxScriptExecTime) {
                    $this->slackClient->send(new Metadata(
                        sprintf('The command %s is taking more than %d', $this->getName(), $maxScriptExecTime),
                        '#cron_jobs',
                        [sprintf('The command %s is taking more than %d. It will shutdown', $this->getName(), $maxScriptExecTime)]
                    ));
                });

                break;
            }
        }

        $this->output->writeln(sprintf(
            '<info>Command finished with %d entites remove from cache</info>',
            $totalNumFilesDeleted
        ));

        $this->outputMemoryUsage();
    }
    /**
     * @param int $limit
     * @param int $offset
     * @return \Generator|null
     */
    private function getPaginatedCacheEntries(
        int $limit,
        int $offset
    ): ?array {
        $searchCaches = $this->searchCacheRepository->findBy([], null, $limit, $offset);

        if (empty($searchCaches)) {
            return null;
        }

        return [
            'generator' => Util::createGenerator($searchCaches),
            'totalItems' => count($searchCaches),
        ];
    }

    private function outputMemoryUsage(): void
    {
        $this->output->writeln(sprintf(
            '<info>Memory: %fMB; Peak memory: %fMB</info>',
            memory_get_usage() / 1024 / 1024,
            memory_get_peak_usage() / 1024 / 1024
        ));
    }
}