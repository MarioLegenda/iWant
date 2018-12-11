<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\ExternalServiceReport;
use App\Doctrine\Repository\ExternalServiceReportRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddServiceReportTypes extends BaseCommand
{
    /**
     * @var ExternalServiceReportRepository $externalServiceReportRepository
     */
    private $externalServiceReportRepository;
    /**
     * @var array $externalServiceReports
     */
    private $externalServiceReports;
    /**
     * CompleteCacheRemove constructor.
     * @param array $externalServiceReports
     * @param ExternalServiceReportRepository $externalServiceReportRepository
     */
    public function __construct(
        ExternalServiceReportRepository $externalServiceReportRepository,
        array $externalServiceReports
    ) {
        $this->externalServiceReports = $externalServiceReports;
        $this->externalServiceReportRepository = $externalServiceReportRepository;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:add_service_report_types');
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

        foreach ($this->externalServiceReports as $reportName) {
            $externalServiceReport = $this->externalServiceReportRepository->tryGetByType($reportName);

            if (!$externalServiceReport instanceof ExternalServiceReport) {
                $this->externalServiceReportRepository->persistAndFlush(
                    new ExternalServiceReport($reportName, [
                        'hitCount' => 0,
                        'characterCount' => 0,
                    ])
                );
            }
        }
    }
}