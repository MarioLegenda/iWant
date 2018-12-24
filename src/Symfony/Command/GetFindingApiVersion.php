<?php

namespace App\Symfony\Command;

use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Response\FindingApi\XmlFindingApiResponseModel;
use App\Ebay\Presentation\FindingApi\EntryPoint\FindingApiEntryPoint;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\FindingApi\Model\GetVersion;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetFindingApiVersion extends BaseCommand
{
    /**
     * @var FindingApiEntryPoint $findingApiEntryPoint
     */
    private $findingApiEntryPoint;
    /**
     * GetFindingApiVersion constructor.
     * @param FindingApiEntryPoint $findingApiEntryPoint
     */
    public function __construct(
        FindingApiEntryPoint $findingApiEntryPoint
    ) {
        $this->findingApiEntryPoint = $findingApiEntryPoint;

        parent::__construct();
    }
    /**
     * @void
     */
    public function configure()
    {
        $this->setName('app:get_finding_api_version');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $this->output->writeln('<info>Getting the eBay Finding API version</info>');

        /** @var XmlFindingApiResponseModel $versionResponseModel */
        $versionResponseModel = $this->findingApiEntryPoint->getVersion($this->getModel());

        $this->checkError($versionResponseModel);

        $this->output->writeln(sprintf(
            '<info>Finding API version: %s</info>',
            $versionResponseModel->getRoot()->getVersion()
        ));
    }
    /**
     * @return FindingApiRequestModelInterface
     */
    private function getModel(): FindingApiRequestModelInterface
    {
        $getVersion = new GetVersion();

        return new FindingApiModel($getVersion, []);
    }
    /**
     * @param XmlFindingApiResponseModel $responseModel
     */
    private function checkError(XmlFindingApiResponseModel $responseModel)
    {
        if ($responseModel->isErrorResponse()) {
            $message = sprintf(
                'Response returned an error with response: %s',
                $responseModel->getRawResponse()
            );

            throw new \RuntimeException($message);
        }
    }
}