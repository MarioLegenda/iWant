<?php

namespace App\Symfony\Command;

use App\Cache\Cache\ApiRequestCache;
use App\Library\Util\Util;
use BlueDot\BlueDot;
use BlueDot\Entity\Entity;
use BlueDot\Entity\PromiseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StaleRequestCacheRemove extends BaseCommand
{
    /**
     * @var ApiRequestCache $apiRequestCache
     */
    private $apiRequestCache;
    /**
     * @var BlueDot $blueDot
     */
    private $blueDot;

    public function __construct(
        ApiRequestCache $apiRequestCache,
        BlueDot $blueDot
    ) {
        $this->apiRequestCache = $apiRequestCache;
        $this->blueDot = $blueDot;

        parent::__construct();
    }

    public function configure()
    {
        $this->setName('app:clear_stale_request_cache');
    }

    public function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->makeEasier($input, $output);

        $this->blueDot->useRepository('command');

        $offset = 0;
        $limit = 10;
        $deletedCount = 0;

        $this->output->writeln(sprintf(
            '<info>Starting command %s with limit %d and offset %d</info>',
            $this->getName(),
            $limit,
            $offset
        ));

        for (;;) {
            /** @var PromiseInterface $promise */
            $promise = $this->blueDot->execute('simple.select.paginate_request_cache', [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            $entity = $promise->getOriginalEntity();
            $data = $entity['data'];

            if (empty($data)) {
                $this->output->writeln(sprintf(
                    '<info>Command %s did not found any more records to delete. Finishing command with limit %d and offset %d</info>',
                    $this->getName(),
                    $limit,
                    $offset
                ));

                break;
            }

            $dataGen = Util::createGenerator($data);

            foreach ($dataGen as $entry) {
                $item = $entry['item'];

                if (Util::toDateTime()->getTimestamp() > $item['expires_at']) {
                    $id = $item['id'];

                    $this->blueDot->execute('simple.delete.delete_request_cache_entry_by_id', [
                        'id' => $id,
                    ]);

                    $deletedCount += 1;
                }
            }

            $offset += $limit;
        }

        $this->output->writeln(sprintf(
            '<info>Command finished with offset %d. %d records deleted.</info>',
            $offset,
            $deletedCount
        ));
    }
}