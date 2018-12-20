<?php

namespace App\Symfony\Command\Util;

use App\Ebay\Business\Finder;
use App\Ebay\Library\ItemFilter\ItemFilter;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\ShoppingApi\GetUserProfileResponseInterface;
use App\Ebay\Presentation\Model\Query;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Symfony\Command\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Ebay\Presentation\ShoppingApi\Model\GetUserProfile as GetUserProfileModel;

class ViewUserProfile extends BaseCommand
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * LoadProductsFromEbayStore constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:view_user_profile');

        $this->addArgument('store_name', InputArgument::REQUIRED, 'Store name');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $shoppingApiModel = $this->createFindingApiRequestModel();

        /** @var GetUserProfileResponseInterface|ResponseModelInterface $response */
        $response = $this->finder->getUserProfile($shoppingApiModel);

        $output->writeln(sprintf(
            '<info>Store name: %s</info>', $response->getUser()->getStoreName()
        ));

        $userAsArray = $response->getUser()->toArray();

        foreach ($userAsArray as $keyType => $keyValue) {
            $output->writeln(
                sprintf('<info>%s: %s</info>', ucfirst($keyType), $keyValue)
            );
        }
    }

    private function createFindingApiRequestModel(): ShoppingApiRequestModelInterface
    {
        $callnameQuery = new Query(
            'callname',
            'GetUserProfile'
        );

        $userIdQuery = new Query(
            'UserId',
            $this->input->getArgument('store_name')
        );

        $includeSelectorQuery = new Query(
            'IncludeSelector',
            'Details'
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $callnameQuery;
        $queries[] = $userIdQuery;
        $queries[] = $includeSelectorQuery;

        $getUserProfile = new GetUserProfileModel($queries);

        $shoppingApiModel = new ShoppingApiModel(
            $getUserProfile,
            TypedArray::create('integer', ItemFilter::class)
        );

        return $shoppingApiModel;
    }
}