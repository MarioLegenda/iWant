<?php

namespace App\Symfony\Command;

use App\Doctrine\Repository\RequestCacheRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InvalidateRequestCache extends BaseCommand
{
    /**
     * @var RequestCacheRepository $requestCacheRepository
     */
    private $requestCacheRepository;
    /**
     * InvalidateRequestCache constructor.
     * @param RequestCacheRepository $requestCacheRepository
     */
    public function __construct(
        RequestCacheRepository $requestCacheRepository
    ) {
        $this->requestCacheRepository = $requestCacheRepository;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:invalidate_request_cache');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\DBAL\DBALException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $this->output->writeln(sprintf(
            '<info>Starting command %s</info>',
            $this->getName()
        ));

        $em = $this->requestCacheRepository->getManager();

        $this->output->writeln(sprintf(
            '<info>Invalidating request cache</info>'
        ));

        $em->getConnection()->exec('TRUNCATE TABLE request_cache');

        $this->output->writeln(sprintf(
            '<info>Cache invalidated. Command successful. Exiting</info>'
        ));
    }
}