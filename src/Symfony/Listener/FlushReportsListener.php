<?php

namespace App\Symfony\Listener;

use App\Doctrine\Entity\ExternalServiceReport;
use App\Doctrine\Repository\ExternalServiceReportRepository;
use App\Reporting\Library\ReportInterface;
use App\Reporting\Library\ReportsCollector;
use App\Reporting\Presentation\Model\YandexTranslationServiceReport;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

class FlushReportsListener
{
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * @var ReportsCollector $reportsCollector
     */
    private $reportsCollector;
    /**
     * @var ExternalServiceReportRepository $externalServiceReportRepository
     */
    private $externalServiceReportRepository;
    /**
     * @var array $externalServiceReports
     */
    private $externalServiceReports;
    /**
     * HttpExceptionListener constructor.
     * @param LoggerInterface $logger
     * @param ReportsCollector $reportsCollector
     * @param ExternalServiceReportRepository $externalServiceReportRepository
     * @param array $externalServiceReports
     */
    public function __construct(
        LoggerInterface $logger,
        ReportsCollector $reportsCollector,
        ExternalServiceReportRepository $externalServiceReportRepository,
        array $externalServiceReports
    ) {
        $this->logger = $logger;
        $this->reportsCollector = $reportsCollector;
        $this->externalServiceReportRepository = $externalServiceReportRepository;
        $this->externalServiceReports = $externalServiceReports;
    }
    /**
     * @param PostResponseEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {
        if (!$this->reportsCollector->isUpdated()) {
            return;
        }

        foreach ($this->externalServiceReports as $reportName) {
            $externalServiceReport = $this->externalServiceReportRepository->tryGetByType($reportName);

            if (!$externalServiceReport instanceof ExternalServiceReport) {
                $message = sprintf(
                    'Invalid service report given. Reports have to be created with the app:add_service_report_types command before they can be used. %s is not created',
                    $reportName
                );

                throw new \RuntimeException($message);
            }

            /** @var ReportInterface|YandexTranslationServiceReport $newReport */
            $newReport = $this->reportsCollector->getReport($reportName);
            $existingReport = $externalServiceReport->getReportAsArray();

            $existingReport['hitCount'] += $newReport->getHitCount();
            $existingReport['characterCount'] += $newReport->getCharacterCount();

            $externalServiceReport->setReport($existingReport);

            $this->externalServiceReportRepository->persistAndFlush($externalServiceReport);
        }
    }
}